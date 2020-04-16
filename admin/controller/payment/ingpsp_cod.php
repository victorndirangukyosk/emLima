<?php

class ControllerPaymentIngpspCod extends Controller {

    const ING_MODULE = 'ingpsp_cod';

    public function index()
    {
        $this->load->controller('payment/ingpsp_ideal', static::getModuleName());
    }

    static function getModuleName()
    {
        return static::ING_MODULE;
    }
}
