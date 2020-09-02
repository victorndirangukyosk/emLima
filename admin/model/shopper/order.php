<?php

class ModelShopperOrder extends Model
{
    public function getOrder($order_id)
    {
        $sql = 'SELECT s.email, s.telephone, s.fax, o.shipping_address,c.name as shipping_city, o.shipping_contact_no,';
        $sql .= 'o.shipping_name, o.shipping_method, o.payment_method, o.comment,vo.*, ';
        $sql .= '(SELECT os.name FROM '.DB_PREFIX.'order_status os ';
        $sql .= 'WHERE os.order_status_id = vo.order_status_id AND ';
        $sql .= "os.language_id = '".(int) $this->config->get('config_language_id')."') AS status ";
        $sql .= 'FROM `'.DB_PREFIX.'vendor_order` vo ';
        $sql .= 'inner join '.DB_PREFIX.'order o on o.order_id = vo.order_id ';
        $sql .= 'left join '.DB_PREFIX.'city c on c.city_id = o.shipping_city_id ';
        $sql .= 'left join '.DB_PREFIX.'store s on s.store_id = vo.store_id ';
        $sql .= 'WHERE vo.vendor_order_id = "'.$order_id.'"';

        return $this->db->query($sql)->row;
    }

    public function getOrders($data)
    {
        $sql = 'SELECT o.*, (SELECT os.name FROM '.DB_PREFIX.'order_status os ';
        $sql .= 'WHERE os.order_status_id = o.order_status_id AND ';
        $sql .= "os.language_id = '".(int) $this->config->get('config_language_id')."') AS status ";
        $sql .= 'FROM `'.DB_PREFIX.'vendor_order` o';

        if (isset($data['filter_order_status']) && !is_null($data['filter_order_status'])) {
            $sql .= " WHERE o.order_status_id = '".$data['filter_order_status']."'";
        } else {
            $sql .= " WHERE o.order_status_id != ''";
        }

        $sql .= ' AND o.shopper_id="'.$this->user->getId().'"';

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id = '".(int) $data['filter_order_id']."'";
        }

        if (!empty($data['filter_vendor_order_id'])) {
            $sql .= " AND o.vendor_order_id = '".(int) $data['filter_vendor_order_id']."'";
        }

        if (isset($data['filter_payment_status'])) {
            $sql .= " AND o.payment_status = '".(int) $data['filter_payment_status']."'";
        }

        if (isset($data['filter_store_name'])) {
            $sql .= " AND o.store_name = '".(int) $data['filter_store_name']."'";
        }

        if (!empty($data['filter_delivery_date'])) {
            $sql .= " AND DATE(o.delivery_date) = DATE('".$this->db->escape($data['filter_delivery_date'])."')";
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
            'o.vendor_order_id',
            'customer',
            'status',
            'o.store_name',
            'o.delivery_date',
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

    public function getTotalOrders($data)
    {
        $sql = 'SELECT count(o.order_id) as total FROM `'.DB_PREFIX.'vendor_order` o ';

        if (isset($data['filter_order_status']) && !is_null($data['filter_order_status'])) {
            $sql .= " WHERE o.order_status_id = '".$data['filter_order_status']."'";
        } else {
            $sql .= " WHERE o.order_status_id != ''";
        }

        $sql .= ' AND o.shopper_id="'.$this->user->getId().'"';

        if (!empty($data['filter_order_id'])) {
            $sql .= " AND o.order_id = '".(int) $data['filter_order_id']."'";
        }

        if (!empty($data['filter_vendor_order_id'])) {
            $sql .= " AND o.vendor_order_id = '".(int) $data['filter_vendor_order_id']."'";
        }

        if (isset($data['filter_payment_status'])) {
            $sql .= " AND o.payment_status = '".(int) $data['filter_payment_status']."'";
        }

        if (isset($data['filter_store_name'])) {
            $sql .= " AND o.store_name = '".(int) $data['filter_store_name']."'";
        }

        if (!empty($data['filter_delivery_date'])) {
            $sql .= " AND DATE(o.delivery_date) = DATE('".$this->db->escape($data['filter_delivery_date'])."')";
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

        return $this->db->query($sql)->row['total'];
    }
}
