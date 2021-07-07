<?php

final class Tax {

    private $tax_rates = [];
    private $city = '';

    public function __construct($registry) {
        $this->config = $registry->get('config');
        $this->db = $registry->get('db');
        $this->session = $registry->get('session');

        if (isset($this->session->data['shipping_address']['city_id'])) {
            $this->setShippingAddress($this->session->data['shipping_address']['city_id']);
            $this->setCity($this->session->data['shipping_address']['city_id']);
        } elseif (isset($this->session->data['city_id'])) {
            $this->setShippingAddress($this->session->data['city_id']);
            $this->setCity($this->session->data['city_id']);
        } elseif (isset($this->session->data['customer_id'])) {//if customer login
            //get default address city
            $row = $this->db->query('select city_id from ' . DB_PREFIX . 'address a inner join ' . DB_PREFIX . 'customer c on c.address_id = a.address_id WHERE c.customer_id="' . $this->session->data['customer_id'] . '"')->row;

            if ($row) {
                $this->setShippingAddress($row['city_id']);
                $this->setCity($row['city_id']);
            } else {
                $this->setShippingAddress($this->config->get('config_city_id'));
                $this->setCity($this->config->get('config_city_id'));
            }
        } else {
            $this->setShippingAddress($this->config->get('config_city_id'));
            $this->setCity($this->config->get('config_city_id'));
        }
    }

    public function setCity($city_id) {
        $this->city = $city_id;
    }

    public function getCity() {
        return $this->city;
    }

    public function setShippingAddress($city_id) {
        if ($this->config->get('tax_status') == 1) {

            if ($city_id == NULL || $city_id <= 0) {
                $city_id = 32;
            }

            $sql = 'SELECT tr1.tax_class_id, tr2.tax_rate_id, tr2.name, tr2.rate, tr2.type, tr1.priority FROM ' . DB_PREFIX . 'tax_rule tr1 ';
            $sql .= 'LEFT JOIN ' . DB_PREFIX . 'tax_rate tr2 ON (tr1.tax_rate_id = tr2.tax_rate_id) ';
            $sql .= 'INNER JOIN ' . DB_PREFIX . 'tax_rate_to_customer_group tr2cg ON (tr2.tax_rate_id = tr2cg.tax_rate_id) ';
            $sql .= 'LEFT JOIN ' . DB_PREFIX . 'city c ON (tr2.city_id = c.city_id) ';
            $sql .= "WHERE tr2cg.customer_group_id = '" . (int) $this->config->get('config_customer_group_id') . "' ";
            $sql .= "AND c.city_id = '" . (int) $city_id . "' ORDER BY tr1.priority ASC";

            $tax_query = $this->db->query($sql);
            $this->tax_rates = [];
            foreach ($tax_query->rows as $result) {
                $this->tax_rates[$result['tax_class_id']][$result['tax_rate_id']] = [
                    'tax_rate_id' => $result['tax_rate_id'],
                    'name' => $result['name'],
                    'rate' => $result['rate'],
                    'type' => $result['type'],
                    'priority' => $result['priority'],
                ];
            }
        } else {
            $this->tax_rates = [];
        }
    }

    public function calculate($value, $tax_class_id, $calculate = true) {
        if ($tax_class_id && $calculate) {
            $amount = 0;

            $tax_rates = $this->getRates($value, $tax_class_id);

            foreach ($tax_rates as $tax_rate) {
                if ('P' != $calculate && 'F' != $calculate) {
                    $amount += $tax_rate['amount'];
                } elseif ($tax_rate['type'] == $calculate) {
                    $amount += $tax_rate['amount'];
                }
            }

            return $value + $amount;
        } else {
            return $value;
        }
    }

    public function getTax($value, $tax_class_id) {
        $amount = 0;

        $tax_rates = $this->getRates($value, $tax_class_id);

        foreach ($tax_rates as $tax_rate) {
            $amount += $tax_rate['amount'];
        }

        return $amount;
    }

    public function getRateName($tax_rate_id) {
        $tax_query = $this->db->query('SELECT name FROM ' . DB_PREFIX . "tax_rate WHERE tax_rate_id = '" . (int) $tax_rate_id . "'");

        if ($tax_query->num_rows) {
            return $tax_query->row['name'];
        } else {
            return false;
        }
    }

    public function getRates($value, $tax_class_id) {
        $tax_rate_data = [];

        if (isset($this->tax_rates[$tax_class_id])) {
            foreach ($this->tax_rates[$tax_class_id] as $tax_rate) {
                if (isset($tax_rate_data[$tax_rate['tax_rate_id']])) {
                    $amount = $tax_rate_data[$tax_rate['tax_rate_id']]['amount'];
                } else {
                    $amount = 0;
                }

                if ('F' == $tax_rate['type']) {
                    $amount += $tax_rate['rate'];
                } elseif ('P' == $tax_rate['type']) {
                    $amount += ($value / 100 * $tax_rate['rate']);
                }

                $tax_rate_data[$tax_rate['tax_rate_id']] = [
                    'tax_rate_id' => $tax_rate['tax_rate_id'],
                    'name' => $tax_rate['name'],
                    'rate' => $tax_rate['rate'],
                    'type' => $tax_rate['type'],
                    'amount' => $amount,
                ];
            }
        }

        return $tax_rate_data;
    }

    public function has($tax_class_id) {
        return isset($this->taxes[$tax_class_id]);
    }

    public function getRateNameByTaxClassId($tax_class_id) {
        $tax_rule_query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "tax_rule WHERE tax_class_id = '" . (int) $tax_class_id . "'");

        if ($tax_rule_query->num_rows) {
            $tax_rate_query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "tax_rate WHERE tax_rate_id = '" . (int) $tax_rule_query->row['tax_rate_id'] . "'");
        } else {
            return false;
        }

        if ($tax_rate_query->num_rows) {
            return $tax_rate_query->row;
        } else {
            return false;
        }
    }

}
