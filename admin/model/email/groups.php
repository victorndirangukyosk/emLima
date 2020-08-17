<?php

class ModelEmailGroups extends Model {

    public function getGroups() {
        $query = $this->db->query("SELECT * FROM ".DB_PREFIX."email_groups");
		return $query->rows;
    }

    public function addGroup($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "email_groups SET name = '" . $this->db->escape($data['name']) . "', description = '" . $this->db->escape($data['description']));
        $groupId = $this->db->getLastId();
        return $groupId;
    }

    public function editGroup($groupId, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "email_groups SET name = '" . $this->db->escape($data['name']) . "', description = '" . $this->db->escape($data['description']) . "' WHERE id = '" . (int)$groupId . "'");
    }

    public function deleteGroup($groupId) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "email_groups WHERE id = '" . (int)$groupId . "'");
    }
}