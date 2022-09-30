<?php

class ModelSaleCustomerGroup extends Model {

    public function addCustomerGroup($data) {
        $this->db->query('INSERT INTO ' . DB_PREFIX . "customer_group SET approval = '" . (int) $data['approval'] . "', sort_order = '" . (int) $data['sort_order'] . "'");

        $customer_group_id = $this->db->getLastId();

        foreach ($data['customer_group_description'] as $language_id => $value) {
            $this->db->query('INSERT INTO ' . DB_PREFIX . "customer_group_description SET customer_group_id = '" . (int) $customer_group_id . "', language_id = '" . (int) $language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "'");
        }

        return $customer_group_id;
    }

    public function editCustomerGroup($customer_group_id, $data) {
        $this->db->query('UPDATE ' . DB_PREFIX . "customer_group SET approval = '" . (int) $data['approval'] . "', sort_order = '" . (int) $data['sort_order'] . "' WHERE customer_group_id = '" . (int) $customer_group_id . "'");

        $this->db->query('DELETE FROM ' . DB_PREFIX . "customer_group_description WHERE customer_group_id = '" . (int) $customer_group_id . "'");

        foreach ($data['customer_group_description'] as $language_id => $value) {
            $this->db->query('INSERT INTO ' . DB_PREFIX . "customer_group_description SET customer_group_id = '" . (int) $customer_group_id . "', language_id = '" . (int) $language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "'");
        }
    }

    public function deleteCustomerGroup($customer_group_id) {
        $this->db->query('DELETE FROM ' . DB_PREFIX . "customer_group WHERE customer_group_id = '" . (int) $customer_group_id . "'");
        $this->db->query('DELETE FROM ' . DB_PREFIX . "customer_group_description WHERE customer_group_id = '" . (int) $customer_group_id . "'");
        $this->db->query('DELETE FROM ' . DB_PREFIX . "product_discount WHERE customer_group_id = '" . (int) $customer_group_id . "'");
        $this->db->query('DELETE FROM ' . DB_PREFIX . "product_special WHERE customer_group_id = '" . (int) $customer_group_id . "'");
        $this->db->query('DELETE FROM ' . DB_PREFIX . "product_reward WHERE customer_group_id = '" . (int) $customer_group_id . "'");
    }

    public function getCustomerGroup($customer_group_id) {
        $query = $this->db->query('SELECT DISTINCT * FROM ' . DB_PREFIX . 'customer_group cg LEFT JOIN ' . DB_PREFIX . "customer_group_description cgd ON (cg.customer_group_id = cgd.customer_group_id) WHERE cg.customer_group_id = '" . (int) $customer_group_id . "' AND cgd.language_id = '" . (int) $this->config->get('config_language_id') . "'");

        return $query->row;
    }

    public function getPriceCategories() {
        $query = $this->db->query('SELECT DISTINCT price_category FROM ' . DB_PREFIX . 'product_category_prices');

        return $query->rows;
    }

    public function getDiscountPriceCategories() {
        $query = $this->db->query('SELECT DISTINCT price_category FROM ' . DB_PREFIX . 'customer_discount');

        return $query->rows;
    }

    public function getDiscountCategories() {
        $query = $this->db->query('SELECT DISTINCT price_category FROM ' . DB_PREFIX . 'customer_discount');

        return $query->rows;
    }

    public function getParentCompanies() {
        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer WHERE company_name = '" . $price_category . "'");
        return $query->rows;
    }

    public function getPriceCategoriesfilter($price_category) {
        $log = new Log('error.log');
        $log->write('getPriceCategories');
        $log->write($price_category);
        $log->write('getPriceCategories');
        $query = $this->db->query('SELECT DISTINCT price_category FROM ' . DB_PREFIX . "product_category_prices WHERE price_category = '" . $price_category . "'");

        return $query->rows;
    }

    public function getDiscountCategoriesfilter($price_category) {
        $log = new Log('error.log');
        $log->write('getPriceCategories');
        $log->write($price_category);
        $log->write('getPriceCategories');
        $query = $this->db->query('SELECT DISTINCT price_category FROM ' . DB_PREFIX . "customer_discount WHERE price_category = '" . $price_category . "'");

        return $query->rows;
    }

    public function getCustomerGroups($data = []) {
        $sql = 'SELECT * FROM ' . DB_PREFIX . 'customer_group cg LEFT JOIN ' . DB_PREFIX . "customer_group_description cgd ON (cg.customer_group_id = cgd.customer_group_id) WHERE cgd.language_id = '" . (int) $this->config->get('config_language_id') . "'";

        $sort_data = [
            'cgd.name',
            'cg.sort_order',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY ' . $data['sort'];
        } else {
            $sql .= ' ORDER BY cgd.name';
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

        return $query->rows;
    }

    public function getCustomerGroupDescriptions($customer_group_id) {
        $customer_group_data = [];

        $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer_group_description WHERE customer_group_id = '" . (int) $customer_group_id . "'");

        foreach ($query->rows as $result) {
            $customer_group_data[$result['language_id']] = [
                'name' => $result['name'],
                'description' => $result['description'],
            ];
        }

        return $customer_group_data;
    }

    public function getTotalCustomerGroups() {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM ' . DB_PREFIX . 'customer_group');

        return $query->row['total'];
    }

    public function getCities() {
        return $this->db->query('select * from `' . DB_PREFIX . 'city`')->rows;
    }

}
