<?php

class Url extends SmartObject
{
    protected $domain;
    protected $ssl;
    protected $rewrite = [];
    protected $config;

    public function __construct($domain, $ssl = '', $registry = '')
    {
        $this->domain = $domain;
        $this->ssl = $ssl;

        if (is_object($registry)) {
            $this->config = $registry->get('config');
        }
    }

    public function addRewrite($rewrite)
    {
        $this->rewrite[] = $rewrite;
    }

    public function link($route, $args = '', $secure = false)
    {
        //print_r($route);
        if (empty($this->ssl)) {
            $this->ssl = str_replace('http://', 'https://', $this->domain);
        }

        if (is_object($this->config)) { // keep B/C alive
            $config_secure = $this->config->get('config_secure');

            if (3 == $config_secure) { // everywhere
                $url = $this->ssl;
            } elseif ((2 == $config_secure) and (IS_ADMIN == false)) { // catalog
                $url = $this->ssl;
            } elseif ((1 == $config_secure) and (IS_ADMIN == false) and (true == $secure)) { // checkout
                $url = $this->ssl;
            } else {
                $url = $this->domain;
            }
        } else {
            if ($secure) {
                $url = $this->ssl;
            } else {
                $url = $this->domain;
            }
        }

        // fix if admin forgot the trailing slash
        if ('/' != substr($url, -1)) {
            //$url .= '/mvggrocery/';
        }

        if ('common/home' == $route) {
            return $url;
        }

        $url .= 'index.php?path='.$route;

        /*echo "url 1";
         echo $args;*/

        if ($args) {
            $url .= str_replace('&', '&amp;', '&'.ltrim($args, '&'));
        }

        // echo "url final";
        // echo $url;
        // echo "end@";
        foreach ($this->rewrite as $rewrite) {
            $url = $rewrite->rewrite($url);
        }
        /*echo "final@@";
        echo $url;die;*/
        return $url;
    }

    public function getDomain($secure = false)
    {
        if (!$secure) {
            $domain = $this->get('domain');
        } else {
            $domain = $this->get('ssl');
        }

        if (empty($domain)) {
            $domain = $this->getFullUrl(false, true);
        }

        return $domain;
    }

    public function getSubdomain()
    {
        return $this->getFullUrl(true);
    }

    public function getFullUrl($path_only = false, $host_only = false)
    {
        $url = '';

        if (false == $host_only) {
            if (false !== strpos(php_sapi_name(), 'cgi') && !ini_get('cgi.fix_pathinfo') && !empty($_SERVER['REQUEST_URI'])) {
                $script_name = $_SERVER['PHP_SELF'];
            } else {
                $script_name = $_SERVER['SCRIPT_NAME'];
            }

            $url = rtrim(dirname($script_name), '/.\\');
        }

        if (false == $path_only) {
            $port = 'http://';
            if (isset($_SERVER['HTTPS']) && (('on' == $_SERVER['HTTPS']) || ('1' == $_SERVER['HTTPS']))) {
                $port = 'https://';
            }

            $url = $port.$_SERVER['HTTP_HOST'].$url;
        }

        if ('/' != substr($url, -1)) {
            // $url .= '/mvggrocery/';
        }

        return $url;
    }

    public function adminLink($route, $args = '', $secure = false)
    {
        //print_r($route);
        $url = HTTPS_CATALOG;

        //echo "<pre>";print_r($url);die;

        // fix if admin forgot the trailing slash
        if ('/' != substr($url, -1)) {
            //$url .= '/mvggrocery/';
        }

        if ('common/home' == $route) {
            return $url;
        }

        $url .= 'index.php?path='.$route;

        /*echo "url 1";
         echo $args;*/

        if ($args) {
            $url .= str_replace('&', '&amp;', '&'.ltrim($args, '&'));
        }

        // echo "url final";
        // echo $url;
        // echo "end@";
        foreach ($this->rewrite as $rewrite) {
            $url = $rewrite->rewrite($url);
        }
        /*echo "final@@";
        echo $url;die;*/
        return $url;
    }
    
    public function adminLinks($route, $args = '', $secure = false)
    {
        //print_r($route);
        $url = HTTP_SERVER;

        //echo "<pre>";print_r($url);die;

        // fix if admin forgot the trailing slash
        if ('/' != substr($url, -1)) {
            //$url .= '/mvggrocery/';
        }

        if ('common/home' == $route) {
            return $url;
        }

        $url .= 'index.php?path='.$route;

        /*echo "url 1";
         echo $args;*/

        if ($args) {
            $url .= str_replace('&', '&amp;', '&'.ltrim($args, '&'));
        }

        // echo "url final";
        // echo $url;
        // echo "end@";
        foreach ($this->rewrite as $rewrite) {
            $url = $rewrite->rewrite($url);
        }
        /*echo "final@@";
        echo $url;die;*/
        return $url;
    }
}
