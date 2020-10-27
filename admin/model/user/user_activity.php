<?php

class ModelUserUserActivity extends Model {

    public function addActivity($key, $data) {
        $dat = $data;
        if (isset($data['user_id'])) {
            $user_id = $data['user_id'];
        } else {
            $user_id = 0;
        }
        unset($dat['user_group_id']);
        $this->db->query('INSERT INTO `' . DB_PREFIX . "user_activity` SET `user_id` = '" . (int) $user_id . "', `user_group_id` = '" . (int) $data['user_group_id'] . "', `key` = '" . $this->db->escape($key) . "', `data` = '" . $this->db->escape(serialize($dat)) . "', `ip` = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', `date_added` = NOW()");
    }
    
}
