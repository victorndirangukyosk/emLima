<?php

class ModelEmailGroups extends Model {

    public function getGroups() {
        $query = $this->db->query("SELECT * FROM ".DB_PREFIX."email_groups");
		return $query->rows;
    }

    public function getGroupById($groupId) {
        $query = $this->db->query("SELECT * FROM ".DB_PREFIX."email_groups WHERE id = '" . (int)$groupId . "'");
		return $query->row;
    }

    public function getGroupByName($groupName) {
        $query = $this->db->query("SELECT * FROM ".DB_PREFIX."email_groups WHERE name = '" . $this->db->escape($groupName) . "'");
		return $query->row;
    }

    public function getCustomersInGroup($groupId) {
        $query = $this->db->query("SELECT customer_id, firstname, lastname, email, telephone FROM ".DB_PREFIX."customer customer NATURAL JOIN ".DB_PREFIX."customer_email_group group_assoc WHERE group_assoc.group_id = '".(int) $groupId."'");
        return $query->rows;
    }

    public function addGroup($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "email_groups SET name = '" . $this->db->escape($data['name']) . "', description = '" . $this->db->escape($data['description']) . "'");
        $groupId = $this->db->getLastId();
        return $groupId;
    }

    public function editGroup($groupId, $data) {
        $this->db->query("UPDATE " . DB_PREFIX . "email_groups SET name = '" . $this->db->escape($data['name']) . "', description = '" . $this->db->escape($data['description']) . "' WHERE id = '" . (int)$groupId . "'");
    }

    public function deleteGroup($groupId) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "email_groups WHERE id = '" . (int)$groupId . "'");
    }

    public function deleteCustomerFromGroup($groupId, $customerId) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "customer_email_group WHERE group_id = '" . (int)$groupId . "' AND customer_id ='" .(int) $customerId . "'");
    }
}