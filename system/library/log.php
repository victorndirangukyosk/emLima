<?php

class Log
{
    private $handle;

    public function __construct($filename)
    {
        $this->handle = fopen(DIR_LOG.date('Y-m-d').'.log', 'a');
    }

    public function write($message)
    {
        fwrite($this->handle, date('Y-m-d G:i:s').' - '.print_r($message, true)."\n");
    }

    public function __destruct()
    {
        fclose($this->handle);
    }
}
