<?php

class ModelApiStats extends Model
{
    public function getDailyOrders($data = [])
    {
        $sql = 'SELECT COUNT(*) AS number, SUM(total) AS price FROM '.DB_PREFIX.'order';

        return $this->getStats($sql, $data, 'order');
    }

    public function getTodaysOrders($date_start, $date_end, $group, $type, $args)
    {
        if ('complete' == $type) {
            $complete_status_ids = '('.implode(',', $this->config->get('config_complete_status')).')';

            $query = $this->db->query('SELECT COUNT(*) AS total,SUM(value) as value  FROM `'.DB_PREFIX.'order` LEFT JOIN '.DB_PREFIX.'order_total on('.DB_PREFIX.'order.order_id = '.DB_PREFIX.'order_total.order_id) WHERE order_status_id IN '.$complete_status_ids.' AND DATE('.DB_PREFIX."order.date_modified) BETWEEN '".$this->db->escape($date_start)."' AND '".$this->db->escape($date_end)."' AND ".DB_PREFIX."order_total.code='sub_total' AND ".DB_PREFIX."order.store_id='".$args['store_id']."'");
        } elseif ('cancelled' == $type) {
            $complete_status_ids = '('.implode(',', $this->config->get('config_refund_status')).')';

            $query = $this->db->query('SELECT COUNT(*) AS total,SUM(value) as value  FROM `'.DB_PREFIX.'order` LEFT JOIN '.DB_PREFIX.'order_total on('.DB_PREFIX.'order.order_id = '.DB_PREFIX.'order_total.order_id) WHERE order_status_id IN '.$complete_status_ids.' AND DATE('.DB_PREFIX."order.date_modified) BETWEEN '".$this->db->escape($date_start)."' AND '".$this->db->escape($date_end)."' AND ".DB_PREFIX."order_total.code='sub_total' AND ".DB_PREFIX."order.store_id='".$args['store_id']."'");
        } else {
            // today created order

            $query = $this->db->query('SELECT COUNT(*) AS total,SUM(value) as value  FROM `'.DB_PREFIX.'order` LEFT JOIN '.DB_PREFIX.'order_total on('.DB_PREFIX.'order.order_id = '.DB_PREFIX.'order_total.order_id) WHERE order_status_id > 0 AND DATE('.DB_PREFIX."order.date_added) BETWEEN '".$this->db->escape($date_start)."' AND '".$this->db->escape($date_end)."' AND ".DB_PREFIX."order_total.code='sub_total' AND ".DB_PREFIX."order.store_id='".$args['store_id']."'");
        }

        return $query->row;
    }

    public function getDailyProducts($data = [])
    {
        $sql = 'SELECT COUNT(*) AS number FROM '.DB_PREFIX.'product';

        return $this->getStats($sql, $data, 'product');
    }

    public function getDailyCustomers($data = [])
    {
        $sql = 'SELECT COUNT(*) AS number FROM '.DB_PREFIX.'customer';

        return $this->getStats($sql, $data, 'customer');
    }

    public function getAdminDailyOrders($data = [])
    {
        $sql = 'SELECT COUNT(*) AS number, SUM(total) AS price FROM '.DB_PREFIX.'order';

        return $this->getAdminStats($sql, $data, 'order');
    }

    public function getAdminDailyProducts($data = [])
    {
        $sql = 'SELECT COUNT(*) AS number FROM '.DB_PREFIX.'product';

        return $this->getAdminStats($sql, $data, 'product');
    }

    public function getAdminDailyCustomers($data = [])
    {
        $sql = 'SELECT COUNT(*) AS number FROM '.DB_PREFIX.'customer';

        return $this->getAdminStats($sql, $data, 'customer');
    }

    public function getStats($sql, $data = [], $type = '')
    {
        $stats = [];

        $date = new DateTime($data['date_from']);
        $date_end = new DateTime($data['date_to']);

        while ($date <= $date_end) {
            $day = $date->format('Y-m-d');

            $query = $this->db->query($sql." WHERE DATE(date_added) = DATE('".$day."')".$this->getExtraConditions($data, $type));

            $row = $query->row;
            $row['date'] = $day;

            if (array_key_exists('price', $row) && is_null($row['price'])) {
                $row['price'] = 0;
            }

            $stats[] = $row;

            $date->add(new DateInterval('P1D'));
        }

        return $stats;
    }

    public function getAdminStats($sql, $data = [], $type = '')
    {
        $stats = [];

        $date = new DateTime($data['date_from']);
        $date_end = new DateTime($data['date_to']);

        while ($date <= $date_end) {
            $day = $date->format('Y-m-d');

            $query = $this->db->query($sql." WHERE DATE(date_added) = DATE('".$day."')".$this->getAdminExtraConditions($data, $type));

            $row = $query->row;
            $row['date'] = $day;

            $stats[] = $row;

            $date->add(new DateInterval('P1D'));
        }

        return $stats;
    }

    private function getExtraConditions($data, $type)
    {
        $sql = '';

        $implode = [];

        if ('order' == $type) {
            if (isset($data['status'])) {
                $implode2 = [];

                $order_statuses = explode(',', $data['status']);

                foreach ($order_statuses as $order_status_id) {
                    $implode2[] = "order_status_id = '".(int) $order_status_id."'";
                }

                if ($implode2) {
                    $implode[] = '('.implode(' OR ', $implode2).')';
                } else {
                    $implode[] = "order_status_id > '0'";
                }
            } else {
                $implode[] = "order_status_id > '0'";
            }
            $implode[] = 'store_id = '.$data['store_id'];
        }

        if ($implode) {
            $sql .= ' AND '.implode(' AND ', $implode);
        }

        return $sql;
    }

    private function getAdminExtraConditions($data, $type)
    {
        $sql = '';

        $implode = [];

        if ('order' == $type) {
            if (isset($data['status'])) {
                $implode2 = [];

                $order_statuses = explode(',', $data['status']);

                foreach ($order_statuses as $order_status_id) {
                    $implode2[] = "order_status_id = '".(int) $order_status_id."'";
                }

                if ($implode2) {
                    $implode[] = '('.implode(' OR ', $implode2).')';
                } else {
                    $implode[] = "order_status_id > '0'";
                }
            } else {
                $implode[] = "order_status_id > '0'";
            }

            if (isset($data['store_id'])) {
                $implode[] = 'store_id = '.$data['store_id'];
            }
        }

        if ($implode) {
            $sql .= ' AND '.implode(' AND ', $implode);
        }

        return $sql;
    }
}
