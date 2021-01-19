<?php

class ModelOrderProcessingGroupOrderProcessingGroup extends Model {

    public function addOrderProcessingGroup($data) {
        $this->db->query('INSERT INTO ' . DB_PREFIX . "order_processing_groups SET order_processing_group_name = '" . $this->db->escape($data['order_processing_group_name']) . "', description = '" . $this->db->escape($data['description']) . "', status = '" . (int) $data['status'] . "', created_at = NOW()");
        $order_processing_group_id = $this->db->getLastId();
        return $order_processing_group_id;
    }

    public function editOrderProcessingGroup($order_processing_group_id, $data) {
        $this->db->query('UPDATE ' . DB_PREFIX . "order_processing_groups SET order_processing_group_name = '" . $this->db->escape($data['order_processing_group_name']) . "', description = '" . $this->db->escape($data['description']) . "', updated_at = '" . NOW() . "' WHERE order_processing_group_id = '" . (int) $order_processing_group_id . "'");
    }

    public function deleteOrderProcessingGroup($order_processing_group_id) {
        $this->db->query('DELETE FROM ' . DB_PREFIX . "order_processing_groups WHERE order_processing_group_id = '" . (int) $order_processing_group_id . "'");
    }

    public function getOrderProcessingGroup($order_processing_group_id) {
        $query = $this->db->query('SELECT DISTINCT * FROM ' . DB_PREFIX . "order_processing_groups WHERE order_processing_group_id = '" . (int) $order_processing_group_id . "'");

        return $query->row;
    }

    public function getOrderProcessingGroupByName($name) {
        $query = $this->db->query('SELECT DISTINCT * FROM ' . DB_PREFIX . "order_processing_groups WHERE order_processing_group_name = '" . $this->db->escape(utf8_strtolower($name)) . "'");

        return $query->row;
    }

    public function getOrderProcessingGroups($data = []) {
        $sql = "SELECT * FROM " . DB_PREFIX . 'order_processing_groups c';

        $implode = [];

        if (!empty($data['name'])) {
            $implode[] = "c.order_processing_group_name LIKE '%" . $this->db->escape($data['name']) . "%'";
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
            'order_processing_group_name',
            'c.status',
            'c.created_at',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY order_processing_group_name';
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

    public function getTotalOrderProcessingGroups($data = []) {
        $sql = 'SELECT COUNT(*) AS total FROM ' . DB_PREFIX . 'order_processing_groups';

        $implode = [];

        if (!empty($data['name'])) {
            $implode[] = "order_processing_group_name LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $implode[] = "status = '" . (int) $data['filter_status'] . "'";
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
