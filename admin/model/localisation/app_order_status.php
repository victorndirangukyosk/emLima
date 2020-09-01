<?php

class Modellocalisationapporderstatus extends Model
{
    public function addOrderStatus($data)
    {
        //echo "<pre>";print_r($data);die;
        foreach ($data['app_order_status'] as $language_id => $value) {
            if (isset($app_order_status_id)) {
                $this->db->query('INSERT INTO '.DB_PREFIX."app_order_status SET app_order_status_id = '".(int) $app_order_status_id."', language_id = '".(int) $language_id."', sort_order = '".$this->db->escape($value['sort_order'])."', name = '".$this->db->escape($value['name'])."', message = '".$this->db->escape($value['message'])."'");
            } else {
                $this->db->query('INSERT INTO '.DB_PREFIX."app_order_status SET language_id = '".(int) $language_id."', sort_order = '".$this->db->escape($value['sort_order'])."', name = '".$this->db->escape($value['name'])."', message = '".$this->db->escape($value['message'])."'");

                $app_order_status_id = $this->db->getLastId();

                // $sql  = 'INSERT INTO `' . DB_PREFIX . 'email` (`text`, `text_id`, `context`, `type`, `status`) VALUES ';
                 // $sql .= "('" . $this->db->escape($value['name']) . "', '" . $app_order_status_id . "', 'status." . strtolower($this->db->escape($value['name'])) ."', 'order', 1);";
                 // $this->db->query($sql);
                 // $email_id = $this->db->getLastId();
            }

            /*$sql  = "INSERT INTO " . DB_PREFIX . "email_description SET";
            $sql .= " email_id = '" . (int)$email_id . "', name = '". $this->db->escape($value['name']) . "', description = '',";
            $sql .= " status = '1', language_id = '". (int)$language_id . "'";
            $this->db->query($sql);*/
        }

        $this->cache->delete('app_order_status');

        return $app_order_status_id;
    }

    public function editOrderStatus($app_order_status_id, $data)
    {
        $this->db->query('DELETE FROM '.DB_PREFIX."app_order_status WHERE app_order_status_id = '".(int) $app_order_status_id."'");

        foreach ($data['app_order_status'] as $language_id => $value) {
            if ('&lt;p&gt;&lt;br&gt;&lt;/p&gt;' == $value['message']) {
                $value['message'] = null;
            }
            $this->db->query('INSERT INTO '.DB_PREFIX."app_order_status SET app_order_status_id = '".(int) $app_order_status_id."', language_id = '".(int) $language_id."', sort_order = '".$this->db->escape($value['sort_order'])."', name = '".$this->db->escape($value['name'])."', message = '".$this->db->escape($value['message'])."'");
        }

        $this->cache->delete('app_order_status');
    }

    public function deleteOrderStatus($app_order_status_id)
    {
        $this->db->query('DELETE FROM '.DB_PREFIX."app_order_status WHERE app_order_status_id = '".(int) $app_order_status_id."'");

        /*$result = $this->db->query("SELECT * FROM " . DB_PREFIX . "email WHERE text_id = '" . (int)$app_order_status_id . "'");
        if(!empty($result->num_rows)) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "email WHERE text_id = '" . (int)$app_order_status_id . "'");
            $this->db->query("DELETE FROM " . DB_PREFIX . "email_description WHERE email_id = '" . (int)$result->row['id'] . "'");
        }*/

        $this->cache->delete('app_order_status');
    }

    public function getOrderStatus($app_order_status_id)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."app_order_status WHERE app_order_status_id = '".(int) $app_order_status_id."' AND language_id = '".(int) $this->config->get('config_language_id')."'");

        return $query->row;
    }

    public function getOrderStatuses($data = [])
    {
        if ($data) {
            $sql = 'SELECT * FROM '.DB_PREFIX."app_order_status WHERE language_id = '".(int) $this->config->get('config_language_id')."'";

            $sql .= ' ORDER BY sort_order';

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
        } else {
            $app_order_status_data = $this->cache->get('app_order_status.'.(int) $this->config->get('config_language_id'));

            if (!$app_order_status_data) {
                $query = $this->db->query('SELECT app_order_status_id, name,sort_order FROM '.DB_PREFIX."app_order_status WHERE language_id = '".(int) $this->config->get('config_language_id')."' ORDER BY sort_order");

                $app_order_status_data = $query->rows;

                $this->cache->set('app_order_status.'.(int) $this->config->get('config_language_id'), $app_order_status_data);
            }

            return $app_order_status_data;
        }
    }

    public function getDeliveryStatuses($data = [])
    {
        $sql = 'SELECT * FROM '.DB_PREFIX.'delivery_statuses';

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function saveDeliveryStatuses($datas)
    {
        $this->db->query('DELETE FROM '.DB_PREFIX.'delivery_statuses ');
        //echo "<pre>";print_r($datas);die;
        foreach ($datas as $data) {
            /*if($data['app_order_status_id'] == '&lt;p&gt;&lt;br&gt;&lt;/p&gt;') {
                $value['message'] = Null;
            }*/

            $this->db->query('INSERT INTO '.DB_PREFIX."delivery_statuses SET app_order_status_id = '".(int) $data['app_order_status_id']."', code = '".$data['code']."', status = '".$data['status']."'");
        }

        //$this->cache->delete('delivery_statuses');
    }

    public function getOrderStatusDescriptions($app_order_status_id)
    {
        $app_order_status_data = [];

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."app_order_status WHERE app_order_status_id = '".(int) $app_order_status_id."'");

        foreach ($query->rows as $result) {
            $app_order_status_data[$result['language_id']] = ['name' => $result['name'], 'sort_order' => $result['sort_order'], 'message' => $result['message']];
        }

        return $app_order_status_data;
    }

    public function getTotalOrderStatuses()
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX."app_order_status WHERE language_id = '".(int) $this->config->get('config_language_id')."'");

        return $query->row['total'];
    }
}
