<?php

class ControllerPaymentInterswitch extends Controller {

    public function index() {

        $data = [];
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/interswitch.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/payment/interswitch.tpl', $data);
        } else {
            return $this->load->view('default/template/payment/interswitch.tpl', $data);
        }
    }

}
