<?php

require_once DIR_SYSTEM.'PHPExcel/Classes/PHPExcel/IOFactory.php';

class Iofactory extends PHPExcel_IOFactory
{
    public function __construct()
    {
        parent::__construct();
    }
}
