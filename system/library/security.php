<?php

class Security extends SmartObject
{
    protected $uri;
    protected $config;

    public function __construct($registry = '')
    {
        if (empty($registry)) {
            return;
        }

        $this->uri = $registry->get('uri');
        $this->config = $registry->get('config');
    }

    public function checkRequest($data, $request = 'get')
    {
        if (!is_object($this->config)) {
            return;
        }

        $uri = urldecode(http_build_query($data));

        if ($this->config->get('config_sec_lfi')) {
            $this->_request('lfi', $request, $uri);
        }

        if ($this->config->get('config_sec_rfi')) {
            $this->_request('rfi', $request, $uri);
        }

        if ($this->config->get('config_sec_sql')) {
            $this->_request('sql', $request, $uri);
        }

        if ($this->config->get('config_sec_xss')) {
            $this->_request('xss', $request, $uri);
        }
    }

    public function _request($type, $request, $uri)
    {
        $config = $this->config->get('config_sec_'.$type);

        if (!is_array($config) or !in_array($request, $config)) {
            return;
        }

        $function = 'is'.strtoupper($type);

        if ($this->$function($uri)) {
            die(strtoupper($type).' attempt.');
        }
    }

    public function isLFI($uri)
    {
        if (preg_match('#\.\/#is', $uri, $match)) {
            return true;
        }

        return false;
    }

    public function isRFI($uri)
    {
        static $exceptions;

        if (!is_array($exceptions)) {
            $exceptions = [];

            // attempt to remove instances of our website from the URL...
            $domain = $this->uri->getHost();
            $exceptions[] = 'http://'.$domain;
            $exceptions[] = 'https://'.$domain;

            // also remove blank entries that do not pose a threat
            $exceptions[] = 'http://&';
            $exceptions[] = 'https://&';
        }

        $uri = str_replace($exceptions, '', $uri);

        if (preg_match('#=https?:\/\/.*#is', $uri, $match)) {
            return true;
        }

        return false;
    }

    public function isSQL($uri)
    {
        if (preg_match('#[\d\W](union select|union join|union distinct)[\d\W]#is', $uri, $match)) {
            return true;
        }

        // check for SQL operations with DB_PREFIX in the URI
        if (preg_match('#[\d\W](union|union select|insert|from|where|concat|into|cast|truncate|select|delete|having)[\d\W]#is', $uri, $match) && preg_match('/'.preg_quote(DB_PREFIX).'/', $uri, $match)) {
            return true;
        }

        return false;
    }

    public function isXSS($uri)
    {
        if (preg_match('#<[^>]*\w*\"?[^>]*>#is', $uri, $match)) {
            return true;
        }

        return false;
    }
}
