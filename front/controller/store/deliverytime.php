<?php

class ControllerStoreDeliverytime extends Controller {

    public function getdeliverytime($store_id) {

        $this->load->model('user/user');
        $store_details = $this->model_user_user->getVendor($store_id);
        $vendor_details = $this->model_user_user->getUser($store_details['vendor_id']);

        return $vendor_details;
    }

}
