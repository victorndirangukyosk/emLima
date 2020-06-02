<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ModelAccountChangepass extends Model {

    Public function index() {
        
    }

    public function change() {

        //  echo "Update `" . DB_PREFIX . "user` set password=$this->request->post['newpassword'] where user_id=" . $this->customer->getid();

        // if ($result !== 0) {
        //    echo "success......";
        // } else {
        //     echo "Unsuccessful";
        // } 
        
        $password=$this->request->post['newpassword'];         
        try{
            $user_id=$this->customer->getid();
            //    echo "<pre>";print_r($user_id);die;
            $this->trigger->fire('pre.customer.edit.password');

           // echo "<pre>";print_r("UPDATE " . DB_PREFIX . "customer SET   temppassword = 0, salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "' WHERE customer_id = '$user_id '");die;

             $this->db->query("UPDATE " . DB_PREFIX . "customer SET   temppassword = 0, salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "' WHERE customer_id = '$user_id'");
    
          $this->trigger->fire('post.customer.edit.password');
          return 1;
        }catch(Exception $e){
            return 0;
        }
  
       


    }

}
