<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class ModelInformationavailability extends Model
{
    public function index()
    {
        if (isset($this->request->get['username'])) {
            $availability_query = $this->db->query('select COUNT(*) from`'.DB_PREFIX."user` where username='".$this->request->get['username']."'");

            if ($availability_query->num_rows) {
                return "<span class='not-available'> Username Not Available.</span>";
            } else {
                return "<span class='available'> Username Available.</span>";
            }
        }
    }
}
