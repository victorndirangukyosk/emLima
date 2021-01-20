<?php

class ModelOrderProcessingGroupOrderProcessor extends Model {

    public function addOrderProcessor($data) {
        $this->db->query('INSERT INTO ' . DB_PREFIX . "order_processors SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', order_processing_group_id = '" . (int) $data['order_processing_group_id'] . "', status = '" . (int) $data['status'] . "', created_at = NOW()");
        $order_processing_group_id = $this->db->getLastId();
        return $order_processing_group_id;
    }

    public function editOrderProcessor($order_processor_id, $data) {
        $this->db->query('UPDATE ' . DB_PREFIX . "order_processors SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', order_processing_group_id = '" . (int) $data['order_processing_group_id'] . "', status = '" . (int) $data['status'] . "', updated_at = NOW() WHERE order_processor_id = '" . (int) $order_processor_id . "'");
    }

    public function deleteOrderProcessor($order_processor_id) {
        $this->db->query('DELETE FROM ' . DB_PREFIX . "order_processors WHERE order_processor_id = '" . (int) $order_processor_id . "'");
    }

    public function getOrderProcessor($order_processor_id) {
        $query = $this->db->query('SELECT DISTINCT * FROM ' . DB_PREFIX . "order_processors WHERE order_processor_id = '" . (int) $order_processor_id . "'");

        return $query->row;
    }

    public function getOrderProcessorByName($name) {
        $query = $this->db->query('SELECT DISTINCT * FROM ' . DB_PREFIX . "order_processors WHERE CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($name) . "%'");

        return $query->row;
    }

    public function getOrderProcessors($data = []) {
        $sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name FROM " . DB_PREFIX . 'order_processors c';

        $implode = [];

        if (!empty($data['filter_name'])) {
            $implode[] = "CONCAT(c.firstname, ' ', c.lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (isset($data['filter_order_processing_group_id']) && !is_null($data['filter_order_processing_group_id'])) {
            $implode[] = "c.order_processing_group_id = '" . (int) $data['filter_order_processing_group_id'] . "'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $implode[] = "c.status = '" . (int) $data['filter_status'] . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(c.created_at) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if ($implode) {
            $sql .= ' WHERE ' . implode(' AND ', $implode);
        }

        $sort_data = [
            'name',
            'c.status',
            'c.created_at',
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

        $query = $this->db->query($sql);

        //echo "<pre>";print_r($sql);die;

        return $query->rows;
    }

    public function getTotalOrderProcessors($data = []) {
        $sql = 'SELECT COUNT(*) AS total FROM ' . DB_PREFIX . 'order_processors';

        $implode = [];

        if (!empty($data['filter_name'])) {
            $implode[] = "CONCAT(firstname, ' ', lastname) LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $implode[] = "status = '" . (int) $data['filter_status'] . "'";
        }

        if (isset($data['filter_order_processing_group_id']) && !is_null($data['filter_order_processing_group_id'])) {
            $implode[] = "order_processing_group_id = '" . (int) $data['filter_order_processing_group_id'] . "'";
        }

        if (!empty($data['filter_date_added'])) {
            $implode[] = "DATE(created_at) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
        }

        if ($implode) {
            $sql .= ' WHERE ' . implode(' AND ', $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

}
