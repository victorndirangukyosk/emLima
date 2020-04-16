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

         echo "Update `" . DB_PREFIX . "user` set password=$this->request->post['newpassword'] where user_id=" . $this->customer->getid();

        if ($result !== 0) {
           echo "success";
        } else {
            echo "Unsuccessful";
        }
    }

}
