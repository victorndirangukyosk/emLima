<?php

class ControllerKraIntegration extends Controller {

    public function index() {
        $data = array();
        $data['fp'] = '';
        $data['DevIp'] = '197.254.20.107';
        $data['DevTcpPort'] = '8000';
        $data['DevTcpPassword'] = 'Password';

        $response = $this->load->controller('kra/kra/fpServerSetDeviceTcpSettings', $data);
        return $response;
    }

}
