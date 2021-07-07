<?php

class ControllerAmitruckAmitruckstatus extends Controller {

    private $error = [];

    public function index() {
        $log = new Log('error.log');
        $log->write('AMITRUCK STATUS');
        $log->write($this->request->get);
        $log->write('AMITRUCK STATUS');
    }

}
