<?php

class ModelAccountOrder extends Model
{
    public function getStatusById($order_status_id)
    {
        $row = $this->db->query('select * from '.DB_PREFIX.'order_status where language_id="'.$this->config->get('config_language_id').'" AND order_status_id="'.$order_status_id.'"')->row;
        if ($row) {
            return $row['name'];
        }
    }

    public function getOrder($order_id)
    {
        $order_query = $this->db->query('SELECT * ,'.DB_PREFIX.'order.date_added as order_date FROM `'.DB_PREFIX.'order` LEFT JOIN '.DB_PREFIX.'store ON ( '.DB_PREFIX.'store.store_id = '.DB_PREFIX.'order.store_id) LEFT JOIN '.DB_PREFIX.'order_status ON ( '.DB_PREFIX.'order_status.order_status_id = '.DB_PREFIX."order.order_status_id)  WHERE order_id = '".(int) $order_id."' AND customer_id = '".(int) $this->customer->getId()."' AND ".DB_PREFIX."order.order_status_id > '0' ");

        if ($order_query->num_rows) {
            $city_info = $this->db->query('select * from `'.DB_PREFIX.'city` WHERE city_id="'.$order_query->row['shipping_city_id'].'"')->row;

            if ($city_info) {
                $shipping_city = $city_info['name'];
            } else {
                $shipping_city = '';
            }

            return [
                'order_id' => $order_query->row['order_id'],
                'invoice_no' => $order_query->row['invoice_no'],
                'invoice_prefix' => $order_query->row['invoice_prefix'],
                'store_id' => $order_query->row['store_id'],
                'store_name' => $order_query->row['store_name'],
                'store_url' => $order_query->row['store_url'],
                'customer_id' => $order_query->row['customer_id'],
                'firstname' => $order_query->row['firstname'],
                'lastname' => $order_query->row['lastname'],
                'telephone' => $order_query->row['telephone'],
                'store_name' => $order_query->row['store_name'],
                'fax' => $order_query->row['fax'],
                'email' => $order_query->row['email'],
                'payment_method' => $order_query->row['payment_method'],
                'shipping_name' => $order_query->row['shipping_name'],
                'shipping_city_id' => $order_query->row['shipping_city_id'],
                'shipping_city' => $shipping_city,
                'shipping_address' => $order_query->row['shipping_address'],
                'shipping_flat_number' => $order_query->row['shipping_flat_number'],
                'shipping_building_name' => $order_query->row['shipping_building_name'],
                'shipping_landmark' => $order_query->row['shipping_landmark'],
                'shipping_contact_no' => $order_query->row['shipping_contact_no'],
                'shipping_method' => $order_query->row['shipping_method'],
                'comment' => $order_query->row['comment'],
                'total' => $order_query->row['total'],
                'order_status_id' => $order_query->row['order_status_id'],
                'language_id' => $order_query->row['language_id'],
                'currency_id' => $order_query->row['currency_id'],
                'currency_code' => $order_query->row['currency_code'],
                'currency_value' => $order_query->row['currency_value'],
                'date_modified' => $order_query->row['date_modified'],
                'date_added' => $order_query->row['date_added'],
                'ip' => $order_query->row['ip'],
                'delivery_timeslot' => $order_query->row['delivery_timeslot'],
                'delivery_date' => $order_query->row['delivery_date'],
                'store_address' => $order_query->row['address'],
                'store_name' => $order_query->row['store_name'],
                'status' => $order_query->row['name'],
                'order_date' => $order_query->row['order_date'],
                'delivery_id' => $order_query->row['delivery_id'],
                'settlement_amount' => $order_query->row['settlement_amount'],
                'latitude' => $order_query->row['latitude'],
                'longitude' => $order_query->row['longitude'],
            ];
        } else {
            return false;
        }
    }

    public function getOrders($start = 0, $limit = 20)
    {
        if ($start < 0) {
            $start = 0;
        }

        if ($limit < 1) {
            $limit = 1;
        }

        $query = $this->db->query('SELECT o.shipping_zipcode,o.shipping_city_id,o.payment_method,o.shipping_address,o.store_name,o.shipping_name, o.order_id, o.firstname, o.lastname, os.name as status, o.date_added, o.total, o.currency_code, o.currency_value FROM `'.DB_PREFIX.'order` o LEFT JOIN '.DB_PREFIX."order_status os ON (o.order_status_id = os.order_status_id) WHERE o.customer_id = '".(int) $this->customer->getId()."' AND o.order_status_id > '0' AND os.language_id = '".(int) $this->config->get('config_language_id')."' ORDER BY o.order_id DESC LIMIT ".(int) $start.','.(int) $limit);

        return $query->rows;
    }

    public function getOrderProduct($order_id, $order_product_id)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."order_product WHERE order_id = '".(int) $order_id."' AND order_product_id = '".(int) $order_product_id."'");

        return $query->row;
    }

    public function getRealOrderProducts($order_id)
    {
        /*$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");*/
        $query = $this->db->query('SELECT a.*,b.image as image
        FROM `'.DB_PREFIX.'real_order_product` a,`'.DB_PREFIX.'product` b,`'.DB_PREFIX."product_to_store` c
        WHERE b.product_id=c.product_id
        AND a.product_id=c.product_store_id
        AND a.order_id='".$order_id."'");

        $query1 = $this->db->query('SELECT a.*
        FROM `'.DB_PREFIX."real_order_product` a
        WHERE a.order_id='".$order_id."' and a.product_id REGEXP '^-?[^0-9]+$'");

        $p = $query->rows;
        $q = $query1->rows;

        foreach ($q as $key => $value) {
            array_push($p, $value);
        }
        //echo "<pre>";print_r($p);die;

        return $p;
    }

    public function getCityName($city_id)
    {
        $city_info = $this->db->query('select * from `'.DB_PREFIX.'city` WHERE city_id="'.$city_id.'"')->row;

        if ($city_info) {
            $shipping_city = $city_info['name'];
        } else {
            $shipping_city = '';
        }

        return $shipping_city;
    }

    public function getCityState($city_id)
    {
        $shipping_state = '';

        $city_info = $this->db->query('select * from `'.DB_PREFIX.'city` WHERE city_id="'.$city_id.'"')->row;

        if ($city_info) {
            $state_info = $this->db->query('select * from `'.DB_PREFIX.'state` WHERE state_id="'.$city_info['state_id'].'"')->row;

            if ($state_info) {
                $shipping_state = $state_info['name'];
            }
        }

        return $shipping_state;
    }

    public function getOrderProductByProductId($order_id, $product_id)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."order_product WHERE order_id = '".(int) $order_id."' AND product_id = '".(int) $product_id."'");

        return $query->row;
    }

    public function getOrderProducts($order_id)
    {
        /*$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");*/
        $query = $this->db->query('SELECT a.*,b.image as image
        FROM `'.DB_PREFIX.'order_product` a,`'.DB_PREFIX.'product` b,`'.DB_PREFIX."product_to_store` c
        WHERE b.product_id=c.product_id
        AND a.product_id=c.product_store_id
        AND a.order_id='".$order_id."'");

        return $query->rows;
    }

    public function getOrderOptions($order_id, $order_product_id)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."order_option WHERE order_id = '".(int) $order_id."' AND order_product_id = '".(int) $order_product_id."'");

        return $query->rows;
    }

    public function getOrderVouchers($order_id)
    {
        $query = $this->db->query('SELECT * FROM `'.DB_PREFIX."order_voucher` WHERE order_id = '".(int) $order_id."'");

        return $query->rows;
    }

    public function getOrderTotals($order_id)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."order_total WHERE order_id = '".(int) $order_id."' ORDER BY sort_order");

        return $query->rows;
    }

    public function getOrderHistories($order_id)
    {
        $query = $this->db->query('SELECT date_added, os.name AS status, oh.comment, oh.notify FROM '.DB_PREFIX.'order_history oh LEFT JOIN '.DB_PREFIX."order_status os ON oh.order_status_id = os.order_status_id WHERE oh.order_id = '".(int) $order_id."' AND os.language_id = '".(int) $this->config->get('config_language_id')."' ORDER BY oh.date_added");

        return $query->rows;
    }

    public function getTotalOrders()
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `'.DB_PREFIX."order` o WHERE customer_id = '".(int) $this->customer->getId()."' AND o.order_status_id > '0' ");

        //return $query;
        return $query->row['total'];
    }

    public function getTotalRealOrderProductsByOrderId($order_id)
    {
        /*$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "real_order_product WHERE order_id = '" . (int) $order_id . "'");

        return $query->row['total'];*/

        $query = $this->db->query('SELECT SUM(quantity) AS total FROM '.DB_PREFIX."real_order_product WHERE order_id = '".(int) $order_id."'");

        return $query->row['total'];
    }

    public function getTotalOrderProductsByOrderId($order_id)
    {
        /*$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int) $order_id . "'");

        return $query->row['total'];*/

        $query = $this->db->query('SELECT SUM(quantity) AS total FROM '.DB_PREFIX."order_product WHERE order_id = '".(int) $order_id."'");

        return $query->row['total'];
    }

    public function getTotalOrderVouchersByOrderId($order_id)
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM `'.DB_PREFIX."order_voucher` WHERE order_id = '".(int) $order_id."'");

        return $query->row['total'];
    }

    public function getStoreById($store_id)
    {
        $sql = 'select * from `'.DB_PREFIX.'store` where store_id ="'.$store_id.'"';

        return $this->db->query($sql)->row;
    }

    public function getOrderDeliveryId($order_id)
    {
        $s = $this->db->query('Select delivery_id from `'.DB_PREFIX."order` WHERE order_id = '".(int) $order_id."'");
        if ($s->num_rows && !empty($s->row['delivery_id'])) {
            return true;
        }

        return false;
    }

    public function getOrderDSDeliveryId($order_id)
    {
        $s = $this->db->query('Select delivery_id from `'.DB_PREFIX."order` WHERE order_id = '".(int) $order_id."'");
        if ($s->num_rows && !empty($s->row['delivery_id'])) {
            return $s->row['delivery_id'];
        }

        return false;
    }
}
