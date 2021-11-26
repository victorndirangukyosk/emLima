<?php

class ModelLocalisationOrderStatus extends Model {

    public function addOrderStatus($data) {
        foreach ($data['order_status'] as $language_id => $value) {
            if (isset($order_status_id)) {
                $this->db->query('INSERT INTO ' . DB_PREFIX . "order_status SET order_status_id = '" . (int) $order_status_id . "', language_id = '" . (int) $language_id . "', name = '" . $this->db->escape($value['name']) . "', sort_order = '" . $this->db->escape($value['sort_order']) . "', color = '" . $this->db->escape($value['color']) . "', message = '" . $this->db->escape($value['message']) . "'");
            } else {
                $this->db->query('INSERT INTO ' . DB_PREFIX . "order_status SET language_id = '" . (int) $language_id . "', name = '" . $this->db->escape($value['name']) . "', sort_order = '" . $this->db->escape($value['sort_order']) . "', color = '" . $this->db->escape($value['color']) . "', message = '" . $this->db->escape($value['message']) . "'");

                $order_status_id = $this->db->getLastId();

                $sql = 'INSERT INTO `' . DB_PREFIX . 'email` (`text`, `text_id`, `context`, `type`, `status`) VALUES ';
                $sql .= "('" . $this->db->escape($value['name']) . "', '" . $order_status_id . "', 'status." . strtolower($this->db->escape($value['name'])) . "', 'order', 1);";
                $this->db->query($sql);
                $email_id = $this->db->getLastId();
            }

            $sql = 'INSERT INTO ' . DB_PREFIX . 'email_description SET';
            $sql .= " email_id = '" . (int) $email_id . "', name = '" . $this->db->escape($value['name']) . "', description = '',";
            $sql .= " status = '1', language_id = '" . (int) $language_id . "'";
            $this->db->query($sql);
        }

        $this->cache->delete('order_status');

        return $order_status_id;
    }

    public function editOrderStatus($order_status_id, $data) {
        $this->db->query('DELETE FROM ' . DB_PREFIX . "order_status WHERE order_status_id = '" . (int) $order_status_id . "'");

        foreach ($data['order_status'] as $language_id => $value) {
            if ('&lt;p&gt;&lt;br&gt;&lt;/p&gt;' == $value['message']) {
                $value['message'] = null;
            }
            $this->db->query('INSERT INTO ' . DB_PREFIX . "order_status SET order_status_id = '" . (int) $order_status_id . "', language_id = '" . (int) $language_id . "', name = '" . $this->db->escape($value['name']) . "', sort_order = '" . $this->db->escape($value['sort_order']) . "', color = '" . $this->db->escape($value['color']) . "', message = '" . $this->db->escape($value['message']) . "'");
        }

        $this->cache->delete('order_status');
    }

    public function deleteOrderStatus($order_status_id) {
        $this->db->query('DELETE FROM ' . DB_PREFIX . "order_status WHERE order_status_id = '" . (int) $order_status_id . "'");

        $result = $this->db->query('SELECT * FROM ' . DB_PREFIX . "email WHERE text_id = '" . (int) $order_status_id . "'");
        if (!empty($result->num_rows)) {
            $this->db->query('DELETE FROM ' . DB_PREFIX . "email WHERE text_id = '" . (int) $order_status_id . "'");
            $this->db->query('DELETE FROM ' . DB_PREFIX . "email_description WHERE email_id = '" . (int) $result->row['id'] . "'");
        }

        $this->cache->delete('order_status');
    }

    public function getOrderStatus($order_status_id) {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "order_status WHERE order_status_id = '" . (int) $order_status_id . "' AND language_id = '" . (int) $this->config->get('config_language_id') . "'");

        return $query->row;
    }

    public function getOrderStatuses($data = []) {
        if ($data) {
            $sql = 'SELECT * FROM ' . DB_PREFIX . "order_status WHERE language_id = '" . (int) $this->config->get('config_language_id') . "'";

            $sql .= ' ORDER BY name';

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

            $query = $this->db->query($sql);

            return $query->rows;
        } else {
            $order_status_data = $this->cache->get('order_status.' . (int) $this->config->get('config_language_id'));

            if (!$order_status_data) {
                $query = $this->db->query('SELECT order_status_id, name FROM ' . DB_PREFIX . "order_status WHERE language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY name");

                $order_status_data = $query->rows;

                $this->cache->set('order_status.' . (int) $this->config->get('config_language_id'), $order_status_data);
            }

            return $order_status_data;
        }
    }

    public function getVendorOrderStatuses($data = []) {
        $sql = 'SELECT * FROM ' . DB_PREFIX . "vendor_order_status WHERE language_id = '" . (int) $this->config->get('config_language_id') . "'";

        $sql .= ' ORDER BY name';

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

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getOrderStatusesbeforeReadyforDelivery() {
        $query = $this->db->query('SELECT order_status_id, name FROM ' . DB_PREFIX . "order_status WHERE language_id = '" . (int) $this->config->get('config_language_id') . "' and order_status_id in(1,14,15) ORDER BY name");

        $order_status_data = $query->rows;

        return $order_status_data;
    }

    public function getDeliveryStatuses($data = []) {
        $sql = 'SELECT * FROM ' . DB_PREFIX . 'delivery_statuses';

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getAppOrderStatusMapping($data = []) {
        $sql = 'SELECT * FROM ' . DB_PREFIX . 'app_order_status_mapping';

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function saveAppOrderStatusMapping($datas) {
        //echo "<pre>";print_r($datas);die;
        $this->db->query('TRUNCATE TABLE ' . DB_PREFIX . 'app_order_status_mapping ');

        foreach ($datas as $key => $data) {
            /* if($data['order_status_id'] == '&lt;p&gt;&lt;br&gt;&lt;/p&gt;') {
              $value['message'] = Null;
              } */

            $this->db->query('INSERT INTO ' . DB_PREFIX . "app_order_status_mapping SET order_status_id = '" . (int) $key . "', code = '" . $data['code'] . "', app_order_status_id = '" . $data['app_order_status_id'] . "'");
        }

        //$this->cache->delete('delivery_statuses');
    }

    public function saveDeliveryStatuses($datas) {
        $this->db->query('DELETE FROM ' . DB_PREFIX . 'delivery_statuses ');
        //echo "<pre>";print_r($datas);die;
        foreach ($datas as $data) {
            /* if($data['order_status_id'] == '&lt;p&gt;&lt;br&gt;&lt;/p&gt;') {
              $value['message'] = Null;
              } */

            $this->db->query('INSERT INTO ' . DB_PREFIX . "delivery_statuses SET order_status_id = '" . (int) $data['order_status_id'] . "', code = '" . $data['code'] . "', status = '" . $data['status'] . "'");
        }

        //$this->cache->delete('delivery_statuses');
    }

    public function getOrderStatusDescriptions($order_status_id) {
        $order_status_data = [];

        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "order_status WHERE order_status_id = '" . (int) $order_status_id . "'");

        foreach ($query->rows as $result) {
            $order_status_data[$result['language_id']] = ['name' => $result['name'], 'sort_order' => $result['sort_order'], 'color' => $result['color'], 'message' => $result['message']];
        }

        return $order_status_data;
    }

    public function getTotalOrderStatuses() {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . "order_status WHERE language_id = '" . (int) $this->config->get('config_language_id') . "'");

        return $query->row['total'];
    }

    public function getValidOrderStatuses() {//Cancelled order status will not come under valid
        $query = $this->db->query('SELECT order_status_id, name FROM ' . DB_PREFIX . "order_status WHERE order_status_id!=6 and language_id = '" . (int) $this->config->get('config_language_id') . "' ORDER BY name");

        $order_status_data = $query->rows;

        return $order_status_data;
    }

}
