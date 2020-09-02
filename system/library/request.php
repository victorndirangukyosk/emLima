<?php

class Request
{
    public $get = [];
    public $post = [];
    public $cookie = [];
    public $files = [];
    public $server = [];

    protected $db;
    protected $config;
    protected $security;
    protected $purifier;

    public function __construct($registry = '')
    {
        if (!empty($registry)) {
            $this->db = $registry->get('db');
            $this->config = $registry->get('config');
            $this->security = $registry->get('security');
        }

        $this->get = $this->clean($_GET);
        $this->post = $this->clean($_POST);
        $this->request = $this->clean($_REQUEST);
        $this->cookie = $this->clean($_COOKIE);
        $this->files = $this->clean($_FILES);
        $this->server = $this->clean($_SERVER);

        if (is_object($this->security)) {
            $this->security->checkRequest($this->get, 'get');
            $this->security->checkRequest($this->post, 'post');
        }

        if (is_object($this->config) and $this->config->get('config_sec_htmlpurifier')) {
            $config = HTMLPurifier_Config::createDefault();
            $this->purifier = new HTMLPurifier($config);
        }
    }

    public function clean($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                unset($data[$key]);

                $data[$this->clean($key)] = $this->clean($value);
            }
        } else {
            if (is_object($this->purifier)) {
                $data = $this->purifier->purify($data);
            } else {
                $data = htmlspecialchars($data, ENT_COMPAT, 'UTF-8');
            }
        }

        return $data;
    }

    public function isGet()
    {
        static $status = null;

        if (null === $status) {
            $status = ('GET' === $this->server['REQUEST_METHOD']);
        }

        return $status;
    }

    public function isPost()
    {
        static $status = null;

        if (null === $status) {
            $status = ('POST' === $this->server['REQUEST_METHOD']);
        }

        return $status;
    }

    public function isSSL()
    {
        static $status = null;

        if (null === $status) {
            $https = $this->server['HTTPS'];

            $status = (('on' === $https) or ('1' === $https));
        }

        return $status;
    }

    public function isAjax()
    {
        static $status = null;

        if (null === $status) {
            if (!empty($this->server['HTTP_X_REQUESTED_WITH'])) {
                $x_request = $this->server['HTTP_X_REQUESTED_WITH'];

                $status = ('xmlhttprequest' === strtolower($x_request));
            } else {
                $status = false;
            }
        }

        return $status;
    }
}
