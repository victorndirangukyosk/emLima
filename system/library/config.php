<?php

class Config
{
    private $data = [];

    public function get($key, $default = null)
    {
        return isset($this->data[$key]) ? $this->data[$key] : $default;
    }

    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function has($key)
    {
        return isset($this->data[$key]);
    }

    public function load($filename)
    {
        $file = DIR_CONFIG.$filename.'.php';

        if (file_exists($file)) {
            $_ = [];

            require $file;

            $this->data = array_merge($this->data, $_);
        } else {
            trigger_error('Error: Could not load config '.$filename.'!');
            exit();
        }
    }
}
