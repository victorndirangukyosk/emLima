<?php

class ModelAccountFarmerActivity extends Model {

    public function addActivity($key, $data) {
        if (isset($data['farmer_id'])) {
            $farmer_id = $data['farmer_id'];
        } else {
            $farmer_id = 0;
        }

        $this->db->query('INSERT INTO `' . DB_PREFIX . "farmer_activity` SET `farmer_id` = '" . (int) $farmer_id . "', `key` = '" . $this->db->escape($key) . "', `data` = '" . $this->db->escape(serialize($data)) . "', `ip` = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', `date_added` = NOW()");
    }

}
