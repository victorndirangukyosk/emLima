<?php

class ModelUserFarmerActivity extends Model {

    public function addActivity($key, $data) {
        $dat = $data;
        if (isset($data['farmer_id'])) {
            $farmer_id = $data['farmer_id'];
        } else {
            $farmer_id = 0;
        }
        unset($dat['user_group_id']);
        $this->db->query('INSERT INTO `' . DB_PREFIX . "farmer_activity` SET `farmer_id` = '" . (int) $farmer_id . "', `user_group_id` = '" . (int) $data['user_group_id'] . "', `key` = '" . $this->db->escape($key) . "', `data` = '" . $this->db->escape(serialize($dat)) . "', `ip` = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', `date_added` = NOW()");
    }
    
}
