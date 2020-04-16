<?php


class ModelApiVendorNotifications extends Model
{

    public function getNotifications($data = array())
    {
        
        $sql = 'SELECT * from ' . DB_PREFIX . "vendor_notifications WHERE user_id = ".$this->session->data['api_id'] ." order by created_at desc";

        $query = $this->db->query($sql);

        return $query->rows;

    }

    public function getTotalNotifications($data = array()) {

        $sql = "SELECT COUNT(*) AS total  FROM " . DB_PREFIX . "vendor_notifications WHERE user_id = ".$this->session->data['api_id'];

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function editNotifications($data = array()) {
        
        $sql = 'UPDATE ' . DB_PREFIX . "vendor_notifications SET status = '". $data['action'] ."' WHERE id =".$data['id'];

        $query = $this->db->query($sql);

        return true;
    }

    public function deleteNotification($data = array()) {
        
        $sql = 'Delete from ' . DB_PREFIX . "vendor_notifications WHERE id =".$data['id'];

        $query = $this->db->query($sql);

        return true;
    }

    public function deleteAllNotifications($data = array()) {
        
        $sql = 'Delete from ' . DB_PREFIX . "vendor_notifications  WHERE user_id = ".$this->session->data['api_id'];

        $query = $this->db->query($sql);

        return true;
    }

}
