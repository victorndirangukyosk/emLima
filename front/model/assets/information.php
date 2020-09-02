<?php

class ModelAssetsInformation extends Model
{
    public function getInformation($information_id)
    {
        $query = $this->db->query('SELECT DISTINCT * FROM '.DB_PREFIX.'information i LEFT JOIN '.DB_PREFIX."information_description id ON (i.information_id = id.information_id)  WHERE i.information_id = '".(int) $information_id."' AND id.language_id = '".(int) $this->config->get('config_language_id')."' AND i.status = '1'");

        return $query->row;
    }

    public function getInformations()
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX.'information i LEFT JOIN '.DB_PREFIX."information_description id ON (i.information_id = id.information_id) WHERE id.language_id = '".(int) $this->config->get('config_language_id')."' AND i.status = '1' ORDER BY i.sort_order, LCASE(id.title) ASC");

        return $query->rows;
    }

    public function getInformationLayoutId($information_id)
    {
        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."information_to_layout WHERE information_id = '".(int) $information_id."' AND store_id = '".(int) $this->config->get('config_store_id')."'");

        if ($query->num_rows) {
            return $query->row['layout_id'];
        } else {
            return 0;
        }
    }

    public function saveQuestionResponse($datas)
    {
        $orders = isset($this->session->data['order_id']);

        //echo "<pre>";print_r($this->session->data);die;

        //echo "<pre>";print_r($orders);die;
        //if ( $orders && count($stores) == count($this->session->data['order_id'])) {
        if ($orders) {
            $stores = $this->cart->getStores();

            //echo "<pre>";print_r($stores);die;
            //echo "<pre>";print_r($stores);die;
            //echo "<pre>";print_r($this->session->data['order_id']);die;
            //echo "<pre>";print_r($);die;
            foreach ($stores as $keys => $values) {
                $order_id = $this->session->data['order_id'][$values];

                /*echo "<pre>";print_r($order_id);die;
                echo "<pre>";print_r($datas);die;*/

                // Totals
                $this->db->query('DELETE FROM '.DB_PREFIX."order_questions WHERE order_id = '".(int) $order_id."'");

                foreach ($datas as $key => $value) {
                    //get question from id
                    $response = 0;

                    if ('yes' == $value) {
                        $response = 1;
                    }

                    $query = $this->db->query('SELECT * FROM '.DB_PREFIX."checkout_question_description WHERE checkout_question_id = '".(int) $key."' and language_id='".(int) $this->config->get('config_language_id')."'");

                    if ($query->row && isset($query->row['question'])) {
                        $this->db->query('INSERT INTO '.DB_PREFIX."order_questions SET order_id = '".(int) $order_id."', question = '".$this->db->escape($query->row['question'])."', response = '".$this->db->escape($response)."'");
                    }
                }
            }
        } else {
            return false;
        }

        return true;
    }

    public function getCustomerGroups($data = [])
    {
        $sql = 'SELECT * FROM '.DB_PREFIX.'customer_group cg LEFT JOIN '.DB_PREFIX."customer_group_description cgd ON (cg.customer_group_id = cgd.customer_group_id) WHERE cgd.language_id = '".(int) $this->config->get('config_language_id')."'";

        $sort_data = [
            'cgd.name',
            'cg.sort_order',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY '.$data['sort'];
        } else {
            $sql .= ' ORDER BY cg.sort_order';
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
}
