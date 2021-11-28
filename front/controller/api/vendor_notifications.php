<?php

class ControllerApiVendorNotifications extends Controller
{
    public function getVendor_notifications($args = [])
    {
        $this->load->language('api/vendor_notifications');

        //echo "api/vendor_notifications";

        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('tool/image');
            $this->load->model('api/vendor_notifications');

            $vendor_notifications_total = $this->model_api_vendor_notifications->getTotalNotifications();

            $results = $this->model_api_vendor_notifications->getNotifications();

            $json = $results;
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function editVendor_notification($args = [])
    {
        $this->load->language('api/vendor_notifications');

        $log = new Log('error.log');
        $log->write('editVendor_notification');
        //echo "api/vendor_notifications";
        //echo "<pre>";print_r($args);die;
        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/vendor_notifications');

            $log->write($args);

            if (isset($args['action']) && isset($args['id'])) {
                if ('delete' == $args['action']) {
                    $this->model_api_vendor_notifications->deleteNotification($args);
                    $json['success'] = $this->language->get('text_success_delete');
                } else {
                    $this->model_api_vendor_notifications->editNotifications($args);
                    $json['success'] = $this->language->get('text_success');
                }
            } else {
                $json['error'] = $this->language->get('error_missing_data');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function deleteVendor_notification($args = [])
    {
        $this->load->language('api/vendor_notifications');

        //echo "apiswle/vendor_notifications";
        //echo "<pre>";print_r($args);die;
        $json = [];

        if (!isset($this->session->data['api_id'])) {
            $json['error'] = $this->language->get('error_permission');
        } else {
            $this->load->model('api/vendor_notifications');

            if (isset($args['id'])) {
                $this->model_api_vendor_notifications->deleteAllNotifications($args);
                $json['success'] = $this->language->get('text_success_delete');
            } else {
                $json['error'] = $this->language->get('error_missing_data');
            }
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
}
