<?php

class ModelCatalogQuestion extends Model
{
    public function addCheckoutQuestion($data)
    {
        $this->db->query('INSERT INTO '.DB_PREFIX."checkout_question SET status = '".(int) $data['status']."'");

        $checkout_question_id = $this->db->getLastId();

        foreach ($data['checkout_question_description'] as $language_id => $value) {
            $this->db->query('INSERT INTO '.DB_PREFIX."checkout_question_description SET checkout_question_id = '".(int) $checkout_question_id."', question = '".$this->db->escape($value['question'])."', language_id = '".(int) $language_id."'");
        }

        $this->trigger->fire('post.admin.checkout_question.add', $checkout_question_id);

        return $checkout_question_id;
    }

    public function editCheckoutQuestion($checkout_question_id, $data)
    {
        $this->trigger->fire('pre.admin.checkout_question.edit', $data);

        $this->db->query('UPDATE '.DB_PREFIX."checkout_question SET status = '".(int) $data['status']."' WHERE checkout_question_id = '".(int) $checkout_question_id."'");

        $this->db->query('DELETE FROM '.DB_PREFIX."checkout_question_description WHERE checkout_question_id = '".(int) $checkout_question_id."'");

        foreach ($data['checkout_question_description'] as $language_id => $value) {
            $this->db->query('INSERT INTO '.DB_PREFIX."checkout_question_description SET checkout_question_id = '".(int) $checkout_question_id."', question = '".$this->db->escape($value['question'])."', language_id = '".(int) $language_id."'");
        }

        $this->trigger->fire('post.admin.checkout_question.edit', $checkout_question_id);
    }

    public function deleteCheckoutQuestion($checkout_question_id)
    {
        $this->trigger->fire('pre.admin.coupon.delete', $checkout_question_id);

        $this->db->query('DELETE FROM '.DB_PREFIX."checkout_question WHERE checkout_question_id = '".(int) $checkout_question_id."'");

        $this->db->query('DELETE FROM '.DB_PREFIX."checkout_question_description WHERE checkout_question_id = '".(int) $checkout_question_id."'");

        $this->trigger->fire('post.admin.coupon.delete', $checkout_question_id);
    }

    public function getCheckoutQuestionDetails($checkout_question_id)
    {
        $query = $this->db->query('SELECT * from  '.DB_PREFIX."checkout_question WHERE checkout_question_id = '".(int) $checkout_question_id."'");

        $checkout_question = $query->row;

        return $checkout_question;
    }

    public function getCheckoutQuestionDescriptions($checkout_question_id)
    {
        $product_description_data = [];

        $query = $this->db->query('SELECT * FROM '.DB_PREFIX."checkout_question_description WHERE checkout_question_id = '".(int) $checkout_question_id."'");

        foreach ($query->rows as $result) {
            $product_description_data[$result['language_id']] = [
                'question' => $result['question'],
                'description' => $result['description'],
            ];
        }

        return $product_description_data;
    }

    public function getCheckoutQuestion($data = [])
    {
        //$sql = "SELECT * FROM " . DB_PREFIX . "checkout_question pc" ;

        $sql = 'SELECT * FROM   `'.DB_PREFIX.'checkout_question` pc  JOIN `'.DB_PREFIX.'checkout_question_description`  pcd ON pcd.checkout_question_id = pc.checkout_question_id';

        $isWhere = 0;
        $_sql = [];

        if (true) {
            $isWhere = 1;
            $_sql[] = "pcd.language_id= '".(int) $this->config->get('config_language_id')."'";
        }

        if (isset($data['filter_question']) && !is_null($data['filter_question'])) {
            $isWhere = 1;

            $_sql[] = "pcd.question LIKE '".$this->db->escape($data['filter_question'])."%'";
        }

        /*if (isset($data['meta_description']) && !is_null($data['meta_description'])) {
            $isWhere = 1;

            $_sql[] = "pc.meta_description = '" . $this->db->escape($data['meta_description']) . "'" ;
        }*/

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $isWhere = 1;

            $_sql[] = "pc.status = '".$this->db->escape($data['filter_status'])."'";
        }

        if ($isWhere) {
            $sql .= ' WHERE '.implode(' AND ', $_sql);
        }

        $sort_data = [
            'question',
            'meta_description',
            'meta_keywords',
            'content',
            'status',
        ];

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= ' ORDER BY '.$data['sort'];
        } else {
            $sql .= ' ORDER BY question';
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

    public function getTotalCheckoutQuestion()
    {
        $query = $this->db->query('SELECT COUNT(*) AS total FROM '.DB_PREFIX.'checkout_question');

        return $query->row['total'];
    }

    public function getTotalCheckoutQuestionFilter($data)
    {
        //$sql = ("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "checkout_question");

        //$sql = "SELECT count(*) as total FROM " . DB_PREFIX . "checkout_question pc";
        $sql = 'SELECT count(*) as total FROM   `'.DB_PREFIX.'checkout_question` pc  JOIN `'.DB_PREFIX.'checkout_question_description`  pcd ON pcd.checkout_question_id = pc.checkout_question_id ';

        $isWhere = 0;
        $_sql = [];

        if (true) {
            $isWhere = 1;
            $_sql[] = "pcd.language_id= '".(int) $this->config->get('config_language_id')."'";
        }

        if (isset($data['filter_question']) && !is_null($data['filter_question'])) {
            $isWhere = 1;

            $_sql[] = "pcd.question LIKE '".$this->db->escape($data['filter_question'])."%'";
        }

        if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
            $isWhere = 1;

            $_sql[] = "pc.status = '".$this->db->escape($data['filter_status'])."'";
        }

        if ($isWhere) {
            $sql .= ' WHERE '.implode(' AND ', $_sql);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }
}
