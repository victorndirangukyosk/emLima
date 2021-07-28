<?php

class ControllerStoreDeliverytime extends Controller {

    public function getdeliverytime() {

        $log = new Log('error.log');
        $log->write('getdeliverytime');
        $store_id = $this->request->post['store_id'];
        $new_time = NULL;
        $this->load->model('user/user');
        $store_details = $this->model_user_user->getVendor($store_id);
        $vendor_details = $this->model_user_user->getUser($store_details['vendor_id']);
        if ($vendor_details['delivery_time'] != NULL && $vendor_details['delivery_time'] > 0) {
            $new_time = date("Y-m-d H:i:s", strtotime('+' . $vendor_details['delivery_time'] . ' hours'));
        }
        $log->write($new_time);
        $log->write('getdeliverytime');

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($new_time));
        //return $new_time;
    }

}
