<?php

class Response
{
    private $headers = [];
    private $level = 0;
    private $output;

    public function get($key)
    {
        return isset($this->$key) ? $this->$key : $key;
    }

    public function addHeader($header)
    {
        $this->headers[] = $header;
    }

    public function redirect($url, $status = 302)
    {
        header('Location: ' . str_replace(['&amp;', "\n", "\r"], ['&', '', ''], $url), true, $status);
        exit();
    }

    public function setCompression($level)
    {
        $this->level = $level;
    }

    public function setOutput($output)
    {
        $this->addHeader('Access-Control-Allow-Origin: *');
        $this->addHeader('Access-Control-Allow-Methods: GET,PUT,POST,DELETE,PATCH,OPTIONS');
        $this->addHeader('Access-Control-Allow-Headers: *');
        $this->output = $output;
    }

    public function getOutput()
    {
        return $this->output;
    }

    private function compress($data, $level = 0)
    {
        if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && (false !== strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))) {
            $encoding = 'gzip';
        }

        if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && (false !== strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'x-gzip'))) {
            $encoding = 'x-gzip';
        }

        if (!isset($encoding) || ($level < -1 || $level > 9)) {
            return $data;
        }

        if (!extension_loaded('zlib') || ini_get('zlib.output_compression')) {
            return $data;
        }

        if (headers_sent()) {
            return $data;
        }

        if (connection_status()) {
            return $data;
        }

        $this->addHeader('Content-Encoding: ' . $encoding);

        return gzencode($data, (int) $level);
    }

    public function output()
    {
        if ($this->output) {
            if ($this->level) {
                $output = $this->compress($this->output, $this->level);
            } else {
                $output = $this->output;
            }

            if (!headers_sent()) {
                foreach ($this->headers as $header) {
                    header($header, true);
                }
            }

            echo $output;
        }
    }
}
