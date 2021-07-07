<?php

class ControllerAmitruckAmitruckquotes extends Controller {

    private $error = [];

    public function index() {
        $log = new Log('error.log');
        $log->write('AMITRUCK QUOTES');
        $log->write($this->request->get);
        $log->write('AMITRUCK QUOTES');
    }

}
