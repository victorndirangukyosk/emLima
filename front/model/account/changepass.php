<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ModelAccountChangepass extends Model {

    public function index() {
        
    }

    public function change() {
        //  echo "Update `" . DB_PREFIX . "user` set password=$this->request->post['newpassword'] where user_id=" . $this->customer->getid();
        // if ($result !== 0) {
        //    echo "success......";
        // } else {
        //     echo "Unsuccessful";
        // }

        $password = $this->request->post['newpassword'];
        try {
            $user_id = $this->customer->getid();
            //    echo "<pre>";print_r($user_id);die;
            $this->trigger->fire('pre.customer.edit.password');

            // echo "<pre>";print_r("UPDATE " . DB_PREFIX . "customer SET   temppassword = 0, salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "' WHERE customer_id = '$user_id '");die;

            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET   temppassword = 0, salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "' WHERE customer_id = '$user_id'");
            $this->savepassword($user_id, $password);

            $this->trigger->fire('post.customer.edit.password');

            return 1;
        } catch (Exception $e) {
            return 0;
        }
    }

    public function changePassword() {
        //  echo "Update `" . DB_PREFIX . "user` set password=$this->request->post['newpassword'] where user_id=" . $this->customer->getid();
        // if ($result !== 0) {
        //    echo "success......";
        // } else {
        //     echo "Unsuccessful";
        // }

        $password = $this->request->post['newpassword'];
        try {
            $user_id = $this->request->post['customerid'];
            //    echo "<pre>";print_r($user_id);die;
            $this->trigger->fire('pre.customer.edit.password');

            // echo "<pre>";print_r("UPDATE " . DB_PREFIX . "customer SET   temppassword = 0, salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "' WHERE customer_id = '$user_id '");die;

            $this->db->query('UPDATE ' . DB_PREFIX . "customer SET   temppassword = 0, salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "' WHERE customer_id = '$user_id'");
            $this->savepassword($user_id, $password);
            $this->trigger->fire('post.customer.edit.password');

            return 1;
        } catch (Exception $e) {
            return 0;
        }
    }

    public function check_customer_previous_password($customer_id, $password) {
        $user_query = $this->db->query('SELECT * FROM ' . DB_PREFIX . "customer WHERE customer_id = '" . $customer_id . "' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape($password) . "'))))) OR password = '" . $this->db->escape(md5($password)) . "')");
        return $user_query->num_rows;
    }

    public function savepassword($customer_id, $password) {
        $this->db->query('INSERT INTO ' . DB_PREFIX . "customer SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "', customer_id = '" . $customer_id . "', created_at = NOW()");
    }

}
