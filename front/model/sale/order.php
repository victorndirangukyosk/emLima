<?php

class ModelSaleOrder extends Model {

    public function getStatusNameById($store_id) {
        $row = $this->db->query('select name from ' . DB_PREFIX . 'order_status WHERE language_id= ' . $this->config->get('config_language_id') . ' AND order_status_id = ' . $store_id)->row;
        if ($row) {
            return $row['name'];
        }
    }

    public function getOrder($order_id) {
        $order_query = $this->db->query("SELECT *, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . 'customer c WHERE c.customer_id = o.customer_id) AS customer FROM `' . DB_PREFIX . "order` o WHERE o.order_id = '" . (int) $order_id . "'");

        if ($order_query->num_rows) {
            $reward = 0;

            $order_product_query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");

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
                'shipping_city_id' => $order_query->row['shipping_city_id'],
                'shipping_flat_number' => $order_query->row['shipping_flat_number'],
                'shipping_building_name' => $order_query->row['shipping_building_name'],
                'shipping_landmark' => $order_query->row['shipping_landmark'],
                'shipping_zipcode' => $order_query->row['shipping_zipcode'],
                'parent_approval' => $order_query->row['parent_approval'],
                'SAP_customer_no' => $order_query->row['SAP_customer_no'],
                'SAP_doc_no' => $order_query->row['SAP_doc_no'],
                'head_chef' => $order_query->row['head_chef'],
                'procurement' => $order_query->row['procurement'],
                'po_number' => $order_query->row['po_number'],
                'latitude' => $order_query->row['latitude'],
                'longitude' => $order_query->row['longitude'],
                'affiliate_id' => $order_query->row['affiliate_id'],
                'marketing_id' => $order_query->row['marketing_id'],
                'tracking' => $order_query->row['tracking'],
                'fixed_commission' => $order_query->row['fixed_commission'],
                'delivery_date' => $order_query->row['delivery_date'],
                'delivery_timeslot' => $order_query->row['delivery_timeslot'],
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

    public function getOrders($data = []) {
        $sql = "SELECT c.name as city, o.firstname,o.lastname,o.comment, o.delivery_id, o.vendor_order_status_id,    cust.company_name AS company_name,o.order_id, o.delivery_date, o.delivery_timeslot, o.shipping_method, o.shipping_address, o.payment_method, o.commission, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS status,(SELECT os.color FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS color, o.shipping_code, o.order_status_id,o.store_name,o.store_id,  o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified,o.po_number,o.SAP_customer_no,o.SAP_doc_no,o.paid,o.amount_partialy_paid,o.delivery_charges FROM `" . DB_PREFIX . 'order` o ';

        $sql .= 'left join `' . DB_PREFIX . 'city` c on c.city_id = o.shipping_city_id';
        $sql .= ' LEFT JOIN ' . DB_PREFIX . 'store on(' . DB_PREFIX . 'store.store_id = o.store_id) ';
        $sql .= ' LEFT JOIN ' . DB_PREFIX . 'customer cust on (cust.customer_id = o.customer_id) ';
        //$sql = "SELECT o.order_id, CONCAT(o.firstname, ' ', o.lastname) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int) $this->config->get('config_language_id') . "') AS status, o.shipping_code, o.total, o.currency_code, o.currency_value, o.date_added, o.date_modified FROM `" . DB_PREFIX . 'order` o';

        if (isset($data['filter_order_status'])) {
            $implode = [];

            $order_statuses = explode(',', $data['filter_order_status']);

            foreach ($order_statuses as $order_status_id) {
                $implode[] = "o.order_status_id = '" . (int) $order_status_id . "'";
            }

            if ($implode) {
                $sql .= ' WHERE (' . implode(' OR ', $implode) . ')';
            } else {
                
            }
        } else {
            $sql .= " WHERE o.order_status_id > '0'";
        }

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id = '" . (int) $data['filter_order_id'] . "'";
        }

        if (isset($data['filter_order_id_array']) && !empty($data['filter_order_id_array'])) {
            $sql .= " AND o.order_id IN (" . $data['filter_order_id_array'] . ")";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(o.firstname, ' ', o.lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_date_added'])) {
            $sql .= " AND DATE(o.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(o.date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
        }

        if (!empty($data['filter_total'])) {
            $sql .= " AND o.total = '" . (float) $data['filter_total'] . "'";
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

        $log = new Log('error.log');
        $log->write('Hi');
        $log->write($data);
        $log->write($sql);
        $log->write('Hi');
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getOrderProducts($order_id) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");

        return $query->rows;
    }

    public function getOrderOption($order_id, $order_option_id) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "order_option WHERE order_id = '" . (int) $order_id . "' AND order_option_id = '" . (int) $order_option_id . "'");

        return $query->row;
    }

    public function getOrderOptions($order_id, $order_product_id) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "order_option WHERE order_id = '" . (int) $order_id . "' AND order_product_id = '" . (int) $order_product_id . "'");

        return $query->rows;
    }

    public function getOrderVouchers($order_id) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "order_voucher WHERE order_id = '" . (int) $order_id . "'");

        return $query->rows;
    }

    public function getOrderVoucherByVoucherId($voucher_id) {
        $query = $this->db->query('SELECT * FROM `' . DB_PREFIX . "order_voucher` WHERE voucher_id = '" . (int) $voucher_id . "'");

        return $query->row;
    }

    public function getOrderTotals($order_id) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "order_total WHERE order_id = '" . (int) $order_id . "' ORDER BY sort_order");

        return $query->rows;
    }

    public function getTotalOrders($data = []) {
        $sql = 'SELECT COUNT(*) AS total FROM `' . DB_PREFIX . 'order`';

        if (!empty($data['filter_order_status'])) {
            $implode = [];

            $order_statuses = explode(',', $data['filter_order_status']);

            foreach ($order_statuses as $order_status_id) {
                $implode[] = "order_status_id = '" . (int) $order_status_id . "'";
            }

            if ($implode) {
                $sql .= ' WHERE (' . implode(' OR ', $implode) . ')';
            }
        } else {
            $sql .= " WHERE order_status_id > '0'";
        }

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND order_id = '" . (int) $data['filter_order_id'] . "'";
        }

        if (!empty($data['filter_customer'])) {
            $sql .= " AND CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_customer']) . "%'";
        }

        if (!empty($data['filter_date_added'])) {
            $sql .= " AND DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (!empty($data['filter_date_modified'])) {
            $sql .= " AND DATE(date_modified) = DATE('" . $this->db->escape($data['filter_date_modified']) . "')";
        }

        if (!empty($data['filter_total'])) {
            $sql .= " AND total = '" . (float) $data['filter_total'] . "'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getTotalOrdersByStoreId($store_id) {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `' . DB_PREFIX . "order` WHERE store_id = '" . (int) $store_id . "'");

        return $query->row['total'];
    }

    public function getTotalOrdersByOrderStatusId($order_status_id) {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `' . DB_PREFIX . "order` WHERE order_status_id = '" . (int) $order_status_id . "' AND order_status_id > '0'");

        return $query->row['total'];
    }

    public function getTotalOrdersByProcessingStatus() {
        $implode = [];

        $order_statuses = $this->config->get('config_processing_status');

        foreach ($order_statuses as $order_status_id) {
            $implode[] = "order_status_id = '" . (int) $order_status_id . "'";
        }

        if ($implode) {
            $query = $this->db->query('SELECT COUNT(*) AS total FROM `' . DB_PREFIX . 'order` WHERE ' . implode(' OR ', $implode));

            return $query->row['total'];
        } else {
            return 0;
        }
    }

    public function getTotalOrdersByCompleteStatus() {
        $implode = [];

        $order_statuses = $this->config->get('config_complete_status');

        foreach ($order_statuses as $order_status_id) {
            $implode[] = "order_status_id = '" . (int) $order_status_id . "'";
        }

        if ($implode) {
            $query = $this->db->query('SELECT COUNT(*) AS total FROM `' . DB_PREFIX . 'order` WHERE ' . implode(' OR ', $implode) . '');

            return $query->row['total'];
        } else {
            return 0;
        }
    }

    public function getTotalOrdersByLanguageId($language_id) {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `' . DB_PREFIX . "order` WHERE language_id = '" . (int) $language_id . "' AND order_status_id > '0'");

        return $query->row['total'];
    }

    public function getTotalOrdersByCurrencyId($currency_id) {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `' . DB_PREFIX . "order` WHERE currency_id = '" . (int) $currency_id . "' AND order_status_id > '0'");

        return $query->row['total'];
    }

    public function getOrderData($order_id) {
        $order_query = $this->db->query("SELECT o.*,  CONCAT(c.firstname, ' ', c.lastname) AS customer ,c.customer_id FROM `" . DB_PREFIX . 'order` o   Left Join  `' . DB_PREFIX . "customer` c on  c.customer_id = o.customer_id WHERE o.order_id = '" . (int) $order_id . "'");
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

    public function createInvoiceNo($order_id) {
        $order_info = $this->getOrderData($order_id);

        if ($order_info && !$order_info['invoice_no']) {
            $query = $this->db->query('SELECT MAX(invoice_no) AS invoice_no FROM `' . DB_PREFIX . "order` WHERE invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "'");

            if ($query->row['invoice_no']) {
                $invoice_no = $query->row['invoice_no'] + 1;
            } else {
                $invoice_no = 1;
            }

            $this->db->query('UPDATE `' . DB_PREFIX . "order` SET invoice_no = '" . (int) $invoice_no . "', invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "' WHERE order_id = '" . (int) $order_id . "'");

            return $order_info['invoice_prefix'] . $invoice_no;

            $invoice_sufix = '-' . $order_info['customer_id'] . '-' . $order_id;
            $this->db->query('UPDATE `' . DB_PREFIX . "order` SET invoice_no = '" . (int) $invoice_no . "', invoice_prefix = '" . $this->db->escape($order_info['invoice_prefix']) . "', invoice_sufix = '" . $invoice_sufix . "' WHERE order_id = '" . (int) $order_id . "'");

            return $order_info['invoice_prefix'] . $invoice_no . $invoice_sufix;
        }
    }

    public function getOrderHistories($order_id, $start = 0, $limit = 10) {
        if ($start < 0) {
            $start = 0;
        }

        if ($limit < 1) {
            $limit = 10;
        }

        $query = $this->db->query('SELECT oh.date_added, os.name AS status, oh.comment, oh.notify FROM ' . DB_PREFIX . 'order_history oh LEFT JOIN ' . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int) $order_id . "' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY oh.date_added ASC LIMIT " . (int) $start . ',' . (int) $limit);

        return $query->rows;
    }

    public function getFullOrderHistoriesByOrderId($order_id) {
        $query = $this->db->query('SELECT oh.date_added, os.name AS status, oh.comment, oh.notify FROM ' . DB_PREFIX . 'order_history oh LEFT JOIN ' . DB_PREFIX . "order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '" . (int) $order_id . "' AND os.language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY oh.date_added ASC ");

        return $query->rows;
    }

    public function getTotalOrderHistories($order_id) {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . "order_history WHERE order_id = '" . (int) $order_id . "'");

        return $query->row['total'];
    }

    public function getTotalOrderHistoriesByOrderStatusId($order_status_id) {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . "order_history WHERE order_status_id = '" . (int) $order_status_id . "'");

        return $query->row['total'];
    }

    public function getEmailsByProductsOrdered($products, $start, $end) {
        $implode = [];

        foreach ($products as $product_id) {
            $implode[] = "op.product_id = '" . (int) $product_id . "'";
        }

        $query = $this->db->query('SELECT DISTINCT email FROM `' . DB_PREFIX . 'order` o LEFT JOIN ' . DB_PREFIX . 'order_product op ON (o.order_id = op.order_id) WHERE (' . implode(' OR ', $implode) . ") AND o.order_status_id <> '0' LIMIT " . (int) $start . ',' . (int) $end);

        return $query->rows;
    }

    public function getTotalEmailsByProductsOrdered($products) {
        $implode = [];

        foreach ($products as $product_id) {
            $implode[] = "op.product_id = '" . (int) $product_id . "'";
        }

        $query = $this->db->query('SELECT DISTINCT email FROM `' . DB_PREFIX . 'order` o LEFT JOIN ' . DB_PREFIX . 'order_product op ON (o.order_id = op.order_id) WHERE (' . implode(' OR ', $implode) . ") AND o.order_status_id <> '0'");

        return $query->row['total'];
    }

    public function getOrderIugu($order_id) {
        $sql = 'select * from `' . DB_PREFIX . 'order_iugu`';
        $sql .= ' WHERE order_id="' . $order_id . '" LIMIT 1';

        return $this->db->query($sql)->row;
    }

    public function getOrderTransactionId($order_id) {
        $sql = 'SELECT transaction_id FROM ' . DB_PREFIX . "order_transaction_id WHERE order_id = '" . (int) $order_id . "'";

        $query = $this->db->query($sql);
        if (isset($query->row)) {
            /* $log = new Log('error.log');
              $log->write('order model');
              $log->write($query->row);
              $log->write('order model'); */
            if (array_key_exists('transaction_id', $query->row)) {
                return $query->row['transaction_id'];
            } else {
                return '';
            }
        }

        return null;
    }

    public function getOrderTransactionDetailsId($order_id) {
        $sql = 'SELECT * FROM ' . DB_PREFIX . "transaction_details WHERE order_ids = '" . (int) $order_id . "'";
        $query = $this->db->query($sql);
        return $query->row;
    }

    public function getCustomersNew($data = []) {
        $sql = "SELECT customer_id FROM " . DB_PREFIX . 'customer c LEFT JOIN ' . DB_PREFIX . "customer_group_description cgd ON (c.customer_group_id = cgd.customer_group_id) WHERE cgd.language_id = '" . (int) $this->config->get('config_language_id') . "'";

        $implode = [];

        if (!empty($data['filter_name'])) {
            if ($this->user->isVendor()) {
                $implode[] = "c.firstname LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
            } else {
                $implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
            }
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "c.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (!empty($data['filter_telephone'])) {
            $implode[] = "c.telephone LIKE '" . $this->db->escape($data['filter_telephone']) . "%'";
        }

        if (isset($data['filter_newsletter']) && !is_null($data['filter_newsletter'])) {
            $implode[] = "c.newsletter = '" . (int) $data['filter_newsletter'] . "'";
        }

        if (!empty($data['filter_customer_group_id'])) {
            $implode[] = "c.customer_group_id = '" . (int) $data['filter_customer_group_id'] . "'";
        }

        if (!empty($data['filter_ip'])) {
            $implode[] = "c.ip = '" . $this->db->escape($data['filter_ip']) . "'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $implode[] = "c.status = '" . (int) $data['filter_status'] . "'";
        }

        if (isset($data['filter_payment_terms']) && !is_null($data['filter_payment_terms'])) {
            $implode[] = "c.payment_terms = '" . $data['filter_payment_terms'] . "'";
        }

        if (isset($data['filter_customer_id_array']) && !is_null($data['filter_customer_id_array'])) {
            $implode[] = "c.customer_id IN (" . $data['filter_customer_id_array'] . ")";
        }

        if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
            $implode[] = "c.approved = '" . (int) $data['filter_approved'] . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (isset($data['filter_parent']) && !is_null($data['filter_parent'])) {
            $implode[] = "c.parent = '" . (int) $data['filter_parent'] . "'";
        }

        if ($implode) {
            $sql .= ' AND ' . implode(' AND ', $implode);
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
            $sql .= ' ORDER BY ' . $data['sort'];
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

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }
        $log = new Log('error.log');
        $log->write($sql);
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getCustomers($data = []) {
        $sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name, cgd.name AS customer_group FROM " . DB_PREFIX . 'customer c LEFT JOIN ' . DB_PREFIX . "customer_group_description cgd ON (c.customer_group_id = cgd.customer_group_id) WHERE cgd.language_id = '" . (int) $this->config->get('config_language_id') . "'";

        $implode = [];

        if (!empty($data['filter_name'])) {
            if ($this->user->isVendor()) {
                $implode[] = "c.firstname LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
            } else {
                $implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
            }
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "c.email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (!empty($data['filter_telephone'])) {
            $implode[] = "c.telephone LIKE '" . $this->db->escape($data['filter_telephone']) . "%'";
        }

        if (isset($data['filter_newsletter']) && !is_null($data['filter_newsletter'])) {
            $implode[] = "c.newsletter = '" . (int) $data['filter_newsletter'] . "'";
        }

        if (!empty($data['filter_customer_group_id'])) {
            $implode[] = "c.customer_group_id = '" . (int) $data['filter_customer_group_id'] . "'";
        }

        if (!empty($data['filter_ip'])) {
            $implode[] = "c.ip = '" . $this->db->escape($data['filter_ip']) . "'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $implode[] = "c.status = '" . (int) $data['filter_status'] . "'";
        }

        if (isset($data['filter_payment_terms']) && !is_null($data['filter_payment_terms'])) {
            $implode[] = "c.payment_terms = '" . $data['filter_payment_terms'] . "'";
        }

        if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
            $implode[] = "c.approved = '" . (int) $data['filter_approved'] . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (isset($data['filter_parent']) && !is_null($data['filter_parent'])) {
            $implode[] = "c.parent = '" . (int) $data['filter_parent'] . "'";
        }

        if ($implode) {
            $sql .= ' AND ' . implode(' AND ', $implode);
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
            $sql .= ' ORDER BY ' . $data['sort'];
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

            $sql .= ' LIMIT ' . (int) $data['start'] . ',' . (int) $data['limit'];
        }
        $log = new Log('error.log');
        $log->write($sql);
        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getTotalCustomers($data = []) {
        $sql = 'SELECT COUNT(*) AS total FROM ' . DB_PREFIX . 'customer';

        $implode = [];

        if (!empty($data['filter_name'])) {
            $implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "email LIKE '" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (!empty($data['filter_telephone'])) {
            $implode[] = "telephone LIKE '" . $this->db->escape($data['filter_telephone']) . "%'";
        }

        if (isset($data['filter_newsletter']) && !is_null($data['filter_newsletter'])) {
            $implode[] = "newsletter = '" . (int) $data['filter_newsletter'] . "'";
        }

        if (!empty($data['filter_customer_group_id'])) {
            $implode[] = "customer_group_id = '" . (int) $data['filter_customer_group_id'] . "'";
        }

        if (!empty($data['filter_ip'])) {
            $implode[] = "ip = '" . $this->db->escape($data['filter_ip']) . "'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $implode[] = "status = '" . (int) $data['filter_status'] . "'";
        }

        if (isset($data['filter_approved']) && !is_null($data['filter_approved'])) {
            $implode[] = "approved = '" . (int) $data['filter_approved'] . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if (isset($data['filter_parent']) && !is_null($data['filter_parent'])) {
            $implode[] = "parent = '" . (int) $data['filter_parent'] . "'";
        }

        if ($implode) {
            $sql .= ' WHERE ' . implode(' AND ', $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function hasRealOrderProducts($order_id) {
        $sql = 'SELECT * FROM ' . DB_PREFIX . "real_order_product WHERE order_id = '" . (int) $order_id . "'";

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

    public function getRealOrderProductsItems($order_id, $store_id = 0) {
        $qty = 0;

        $sql = 'SELECT sum(quantity) as quantity FROM ' . DB_PREFIX . "real_order_product WHERE order_id = '" . (int) $order_id . "'";

        if ($store_id) {
            $sql .= " AND store_id='" . $store_id . "'";
        }

        $query = $this->db->query($sql)->row;

        if (isset($query['quantity'])) {
            $qty = $query['quantity'];
        }

        return $qty;
    }

    public function getOrderProductsItems($order_id, $store_id = 0) {
        $qty = 0;

        $sql = 'SELECT sum(quantity) as quantity FROM ' . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'";

        if ($store_id) {
            $sql .= " AND store_id='" . $store_id . "'";
        }

        $query = $this->db->query($sql)->row;

        if (isset($query['quantity'])) {
            $qty = $query['quantity'];
        }

        return $qty;
    }

    public function getStoreData($store_id) {
        return $this->db->query('select * from ' . DB_PREFIX . 'store where store_id =' . $store_id . '')->row;
    }

    public function getOrdersCount() {
        $sql = "SELECT  count(o.order_id) as OrdersCount FROM `" . DB_PREFIX . 'order` o';
        $query = $this->db->query($sql);
        return $query->row['OrdersCount'];
    }

    public function getFarmersCount() {
        $sql = "SELECT  count(f.order_id) as FarmersCount FROM `" . DB_PREFIX . 'farmer` f';
        $query = $this->db->query($sql);
        return $query->row['FarmersCount'];
    }

    public function getCustomersCount() {
        $sql = "SELECT  count(c.customer_id) as CustomersCount FROM `" . DB_PREFIX . 'customer` c';
        $query = $this->db->query($sql);
        return $query->row['CustomersCount'];
    }

    public function addVehicleLatLng($vehicle_number, $latitude, $longitude, $speed) {
        $sql = "SELECT  vehicle_number FROM `" . DB_PREFIX . "amitruck_vehicle` where vehicle_number='" . $this->db->escape($vehicle_number) . "'";

        // echo $sql;die;
        $query = $this->db->query($sql);
        // echo "<pre>";print_r($query->rows['vehicle_number']>0);die;

        if ($query->rows['vehicle_number']) {
            //     $sql='INSERT INTO ' . DB_PREFIX . "amitruck_vehicle SET vehicle_number = '" . $this->db->escape($vehicle_number) . "', latitude = '" . $this->db->escape($latitude) . "', longitude = '" . $this->db->escape($longitude) . "', speed = '" . ($speed) . "', date_added = NOW()";
            // echo $sql;die;

            $this->db->query('UPDATE ' . DB_PREFIX . "amitruck_vehicle SET   latitude = '" . $this->db->escape($latitude) . "', longitude = '" . $this->db->escape($longitude) . "', speed = '" . ($speed) . "', date_added = NOW() where vehicle_number = '" . $this->db->escape($vehicle_number) . "'");
        } else {
            $this->db->query('INSERT INTO ' . DB_PREFIX . "amitruck_vehicle SET vehicle_number = '" . $this->db->escape($vehicle_number) . "', latitude = '" . $this->db->escape($latitude) . "', longitude = '" . $this->db->escape($longitude) . "', speed = '" . ($speed) . "', date_added = NOW()");
        }
        $id = $this->db->getLastId();
        return $id;
    }

    public function getVehicleLatLng($vehicle_number = 0) {


        $sql = "SELECT vehicle_number, latitude,longitude,speed FROM `" . DB_PREFIX . 'amitruck_vehicle` ';

        if (isset($vehicle_number) && $vehicle_number > 0) {
            $sql .= ' WHERE vehicle_number= ' . $vehicle_number;
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function CreateOrder($data) {
        $this->db->query("INSERT INTO `" . DB_PREFIX . "order` SET invoice_prefix = '" . $this->db->escape($data['invoice_prefix']) . "', store_id = '" . (int) $data['store_id'] . "', store_name = '" . $this->db->escape($data['store_name']) . "', store_url = '" . $this->db->escape($data['store_url']) . "', customer_id = '" . (int) $data['customer_id'] . "', customer_group_id = '" . (int) $data['customer_group_id'] . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '') . "', payment_method = '" . $this->db->escape($data['payment_method']) . "', payment_code = '" . $this->db->escape($data['payment_code']) . "', shipping_city_id = '" . $this->db->escape($data['shipping_city_id']) . "', shipping_flat_number = '" . $this->db->escape($data['shipping_flat_number']) . "', shipping_building_name = '" . $this->db->escape($data['shipping_building_name']) . "', shipping_landmark = '" . $this->db->escape($data['shipping_landmark']) . "', shipping_zipcode = '" . $this->db->escape($data['shipping_zipcode']) . "', shipping_code = '" . $this->db->escape($data['shipping_code']) . "', shipping_name = '" . $this->db->escape($data['shipping_name']) . "', shipping_address = '" . $this->db->escape($data['shipping_address']) . "', shipping_contact_no = '" . $this->db->escape($data['shipping_contact_no']) . "', shipping_method = '" . $this->db->escape($data['shipping_method']) . "', comment = '" . $this->db->escape($data['comment']) . "', total = '" . (float) $data['total'] . "', latitude = '" . $data['latitude'] . "',longitude = '" . $data['longitude'] . "', affiliate_id = '" . (int) $data['affiliate_id'] . "', marketing_id = '" . (int) $data['marketing_id'] . "', tracking = '" . $this->db->escape($data['tracking']) . "', language_id = '" . (int) $data['language_id'] . "', currency_id = '" . (int) $data['currency_id'] . "', currency_code = '" . $this->db->escape($data['currency_code']) . "', currency_value = '" . (float) $data['currency_value'] . "', ip = '" . $this->db->escape($data['ip']) . "', forwarded_ip = '" . $this->db->escape($data['forwarded_ip']) . "', user_agent = '" . $this->db->escape($data['user_agent']) . "', accept_language = '" . $this->db->escape($data['accept_language']) . "', commission = '" . $this->db->escape($data['commission']) . "', fixed_commission = '" . $this->db->escape($data['fixed_commission']) . "', delivery_date = '" . $this->db->escape(date('Y-m-d', strtotime($data['delivery_date']))) . "', delivery_timeslot = '" . $this->db->escape($data['delivery_timeslot']) . "', parent_approval = '" . $this->db->escape($data['parent_approval']) . "', SAP_customer_no = '" . $this->db->escape($data['SAP_customer_no']) . "', SAP_doc_no = '" . $this->db->escape($data['SAP_doc_no']) . "', head_chef = '" . $this->db->escape($data['head_chef']) . "', procurement = '" . $this->db->escape($data['procurement']) . "', po_number = '" . $this->db->escape($data['po_number']) . "', login_mode = 'web', order_status_id = 14,  date_added = NOW(), date_modified = NOW()");
        return $this->db->getLastId();
    }

    public function InsertProductsByOrderId($products, $order_id) {
        foreach ($products as $product) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "order_product SET vendor_id='" . (int) $product['vendor_id'] . "', store_id='" . (int) $product['store_id'] . "', product_type='" . $product['product_type'] . "', unit='" . $product['unit'] . "', order_id = '" . (int) $order_id . "', variation_id = '" . (int) $product['variation_id'] . "', product_id = '" . (int) $product['product_id'] . "', general_product_id = '" . (int) $product['general_product_id'] . "', name = '" . $this->db->escape($product['name']) . "', model = '" . $this->db->escape($product['model']) . "', quantity = '" . (float) $product['quantity'] . "', price = '" . (float) $product['price'] . "', total = '" . (float) $product['total'] . "', tax = '" . (float) $product['tax'] . "', reward = '" . (int) $product['reward'] . "'");
        }
        return $this->db->getLastId();
    }

    public function InsertOrderTotals($totals, $order_id) {
        foreach ($totals as $total) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "order_total SET order_id = '" . (int) $order_id . "', code = '" . $this->db->escape($total['code']) . "', title = '" . $this->db->escape($total['title']) . "', `value` = '" . (float) $total['value'] . "', sort_order = '" . (int) $total['sort_order'] . "'");
        }
        return $this->db->getLastId();
    }

    public function InsertOrderTransactionDetails($data, $order_id) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "transaction_details SET order_ids = '" . $order_id . "', customer_id = '" . $data['customer_id'] . "', no_of_products = '" . $this->db->escape($data['no_of_products']) . "', `total` = '" . (float) $data['total'] . "',date_added = NOW()");
        return $this->db->getLastId();
    }

    public function UpdatePaymentMethod($order_id, $payment_method, $payment_code) {
        $this->db->query('UPDATE ' . DB_PREFIX . "order SET   payment_method = '" . $this->db->escape($payment_method) . "', payment_code = '" . $this->db->escape($payment_code) . "' WHERE order_id = '" . (int) $order_id . "'");
    }

    public function getProductsForInventory($filter_name) {

        $store_id = 0;

        $this->db->select('product_to_store.*,product_to_category.category_id,product.*,product_description.*,product_description.name as pd_name', false);
        $this->db->join('product', 'product.product_id = product_to_store.product_id', 'left');
        $this->db->join('product_description', 'product_description.product_id = product_to_store.product_id', 'left');
        $this->db->join('product_to_category', 'product_to_category.product_id = product_to_store.product_id', 'left');

        if (!empty($filter_name)) {
            $this->db->like('product_description.name', $this->db->escape($filter_name), 'both');
        }

        $limit = 18;
        $offset = 0;
        $this->db->group_by('product_description.name');
        $this->db->where('product_to_store.status', 1);
        $this->db->where('product_description.language_id', $this->config->get('config_language_id'));
        $this->db->where('product.status', 1);
        if ($store_id > 0) {
            $this->db->where('product_to_store.store_id', $store_id);
        }
        $ret = $this->db->get('product_to_store', $limit, $offset)->rows;
        return $ret;
    }

    public function getProduct($product_store_id) {
        $query = $this->db->query('SELECT DISTINCT p.*,pd.name,v.user_id as vendor_id FROM ' . DB_PREFIX . 'product_to_store p LEFT JOIN ' . DB_PREFIX . 'product_description pd ON (p.product_id = pd.product_id) LEFT JOIN ' . DB_PREFIX . 'store st ON (st.store_id = p.store_id) LEFT JOIN ' . DB_PREFIX . "user v ON (v.user_id = st.vendor_id) WHERE p.product_store_id = '" . (int) $product_store_id . "' AND pd.language_id = '" . (int) $this->config->get('config_language_id') . "'");

        $product = $query->row;

        return $product;
    }

    public function getVendorProductVariations($product_name, $store_id, $formated = false) {
        $returnData = [];

        $all_variations = 'SELECT * ,product_store_id as variation_id FROM ' . DB_PREFIX . 'product_to_store ps LEFT JOIN ' . DB_PREFIX . "product p ON (ps.product_id = p.product_id) WHERE name = '$product_name' and ps.status=1";
        $log = new Log('error.log');
        $log->write($all_variations);
        $result = $this->db->query($all_variations);

        foreach ($result->rows as $r) {
            if ($r['status']) {
                $key = base64_encode(serialize(['product_store_id' => (int) $r['product_store_id'], 'store_id' => $store_id]));

                $r['key'] = $key;

                $percent_off = null;
                if (isset($r['special_price']) && isset($r['price']) && 0 != $r['price'] && 0 != $r['special_price']) {
                    $percent_off = (($r['price'] - $r['special_price']) / $r['price']) * 100;
                }

                if ((float) $r['special_price']) {
                    $r['special_price'] = $this->currency->formatWithoutCurrency((float) $r['special_price']);
                } else {
                    $r['special_price'] = false;
                }

                $r['model'] = $r['model'];

                $res = [
                    'variation_id' => $r['product_store_id'],
                    'unit' => $r['unit'],
                    'weight' => floatval($r['weight']),
                    'price' => $r['price'],
                    'special' => $r['special_price'],
                    'percent_off' => number_format($percent_off, 0),
                    'key' => $key,
                    'model' => $r['model']
                ];

                if (true == $formated) {
                    array_push($returnData, $res);
                } else {
                    array_push($returnData, $r);
                }
            }
        }

        return $returnData;
    }

}
