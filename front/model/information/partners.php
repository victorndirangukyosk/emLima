<?php

class ModelInformationPartners extends Model {

    public function createPartners($first_name, $last_name, $designation, $company_name, $email, $phone, $description) {

        $this->db->query('INSERT INTO `' . DB_PREFIX . "partners` SET `first_name` = '" . $first_name . "', `last_name` = '" . $last_name . "', `designation` = '" . $designation . "', `company_name` = '" . $company_name . "', `email` = '" . $email . "', `phone` = '" . $phone . "', `description` = '" . $description . "', created_at = NOW()");

        //    echo "<pre>";print_r('INSERT INTO `' . DB_PREFIX . "partners` SET `first_name` = '" . $first_name . "', `last_name` = '" . $last_name . "', `designation` = '" . $designation . "', `company_name` = '" . $company_name . "', `email` = '" . $email . "', `phone` = '" . $phone . "', `description` = '" . $description . "', created_at = NOW()");die;

        return $this->db->getLastId();
    }

}
