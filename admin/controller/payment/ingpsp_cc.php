<?php

class ControllerPaymentIngpspCc extends Controller
{
    const ING_MODULE = 'ingpsp_cc';

    public function index()
    {
        $this->load->controller('payment/ingpsp_ideal', static::getModuleName());
    }

    public static function getModuleName()
    {
        return static::ING_MODULE;
    }
}
