<?php

class Filesystem extends Symfony\Component\Filesystem\Filesystem
{
    public function __construct()
    {
        // extend later
    }

    public function mkdir($dirs, $mode = 0755)
    {
        parent::mkdir($dirs, $mode);
    }

    public function remove($files)
    {
        parent::remove($files);
    }
}
