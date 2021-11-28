<?php

class Installer extends Object
{
    public function __construct($registry)
    {
        $this->cache = $registry->get('cache');
        $this->config = $registry->get('config');
        $this->filesystem = $registry->get('filesystem');
    }

    public function unzip($file)
    {
        $dir = dirname($file);

        $zip = new ZipArchive();

        if (!$zip->open($file)) {
            return false;
        }

        $zip->extractTo($dir);
        $zip->close();

        return true;
    }
}
