<?php

use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

class Catalog extends App
{
    public function initialise()
    {
        // File System
        $this->registry->set('filesystem', new Filesystem());

        // Config
        $this->registry->set('config', new Config());

        // Database
        $this->registry->set('db', new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE));

        if (isset($this->session->data['store_id'])) {
            $this->config->set('config_store_id', $this->session->data['store_id']);
        } else {
            $this->config->set('config_store_id', 0);
        }

        // Settings
        $query = $this->db->query('SELECT * FROM `'.DB_PREFIX."setting` WHERE store_id = '0' OR store_id = '".(int) $this->config->get('config_store_id')."' ORDER BY store_id ASC");

        foreach ($query->rows as $result) {
            if (!$result['serialized']) {
                $this->config->set($result['key'], $result['value']);
            } else {
                $this->config->set($result['key'], unserialize($result['value']));
            }
        }

        $this->config->set('config_url', HTTP_SERVER);
        $this->config->set('config_ssl', HTTPS_SERVER);

        // Loader
        $this->registry->set('load', new Loader($this->registry));

        // Trigger
        $this->registry->set('trigger', new Trigger($this->registry));

        // Url
        $this->registry->set('url', new Url($this->config->get('config_url'), $this->config->get('config_ssl'), $this->registry));

        // Uri
        $this->registry->set('uri', new Uri());

        // Log
        $this->registry->set('log', new Log($this->config->get('config_error_filename')));

        // Error Handler
        if (2 == $this->config->get('config_error_display', 0)) {
            ErrorHandler::register();
            ExceptionHandler::register();
        } else {
            set_error_handler([$this, 'errorHandler']);
        }

        // Security
        $this->registry->set('security', new Security($this->registry));

        // Request
        $this->registry->set('request', new Request($this->registry));

        // Response
        $response = new Response();
        $response->addHeader('Content-Type: text/html; charset=utf-8');
        $response->setCompression($this->config->get('config_compression'));
        $this->registry->set('response', $response);

        // Cache
        $cache = new Cache($this->config->get('config_cache_storage', 'file'), $this->config->get('config_cache_lifetime', 86400));
        $this->registry->set('cache', $cache);

        // Session
        $this->registry->set('session', new Session());

        // Language Detection
        $languages = [];

        $query = $this->db->query('SELECT * FROM `'.DB_PREFIX."language` WHERE status = '1'");

        foreach ($query->rows as $result) {
            $languages[$result['code']] = $result;
        }

        if (isset($this->request->get['lang']) && array_key_exists($this->request->get['lang'], $languages) && $languages[$this->request->get['lang']]['status']) {
            $code = $this->request->get['lang'];
        } elseif (isset($this->session->data['language']) && array_key_exists($this->session->data['language'], $languages) && $languages[$this->session->data['language']]['status']) {
            $code = $this->session->data['language'];
        } elseif (isset($this->request->cookie['language']) && array_key_exists($this->request->cookie['language'], $languages) && $languages[$this->request->cookie['language']]['status']) {
            $code = $this->request->cookie['language'];
        } else {
            $detect = '';

            if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE']) && $this->request->server['HTTP_ACCEPT_LANGUAGE']) {
                $browser_languages = explode(',', $this->request->server['HTTP_ACCEPT_LANGUAGE']);

                foreach ($browser_languages as $browser_language) {
                    foreach ($languages as $key => $value) {
                        if ($value['status']) {
                            $locale = explode(',', $value['locale']);

                            if (in_array($browser_language, $locale)) {
                                $detect = $key;
                                break 2;
                            }
                        }
                    }
                }
            }

            /*echo "<pre>";print_r($browser_languages);
            echo "<pre>";print_r($languages);
            echo "lang detected";print_r($detect);die;*/
            $code = $detect ? $detect : $this->config->get('config_language');
        }

        if (!isset($this->session->data['language']) || $this->session->data['language'] != $code) {
            $this->session->data['language'] = $code;
        }

        if (!isset($this->request->cookie['language']) || $this->request->cookie['language'] != $code) {
            setcookie('language', $code, time() + 60 * 60 * 24 * 30, '/', $this->request->server['HTTP_HOST']);
        }

        $this->config->set('config_language_id', $languages[$code]['language_id']);

        //$this->config->set('config_language', $languages[$code]['code']);

        // Language
        $language = new Language($languages[$code]['directory'], $this->registry);
        $language->load('english');
        $language->load($languages[$code]['directory']);
        $this->registry->set('language', $language);

        // Page Cache
        $pagecache = new Pagecache($this->registry);
        $pagecache->getPage();
        $this->registry->set('pagecache', $pagecache);

        // Document
        $this->registry->set('document', new Document());

        // Utility
        $this->registry->set('utility', new Utility($this->registry));

        //echo "1";
        $this->trigger->fire('post.app.initialise');
    }

    public function ecommerce()
    {
        // Customer
        $this->registry->set('customer', new Customer($this->registry));

        // Customer Group
        if ($this->customer->isLogged()) {
            $this->config->set('config_customer_group_id', $this->customer->getGroupId());
        } elseif (isset($this->session->data['customer']) && isset($this->session->data['customer']['customer_group_id'])) {
            // For API calls
            $this->config->set('config_customer_group_id', $this->session->data['customer']['customer_group_id']);
        } elseif (isset($this->session->data['guest']) && isset($this->session->data['guest']['customer_group_id'])) {
            $this->config->set('config_customer_group_id', $this->session->data['guest']['customer_group_id']);
        }

        // Email Template
        $this->registry->set('emailtemplate', new Emailtemplate($this->registry));

        // Tracking Code
        if (isset($this->request->get['tracking'])) {
            setcookie('tracking', $this->request->get['tracking'], time() + 3600 * 24 * 1000, '/');

            $this->db->query('UPDATE `'.DB_PREFIX."marketing` SET clicks = (clicks + 1) WHERE code = '".$this->db->escape($this->request->get['tracking'])."'");
        }

        // Affiliate
        $this->registry->set('affiliate', new Affiliate($this->registry));

        // Currency
        $this->registry->set('currency', new Currency($this->registry));

        // Tax
        $this->registry->set('tax', new Tax($this->registry));

        // Weight
        $this->registry->set('weight', new Weight($this->registry));

        // Length
        $this->registry->set('length', new Length($this->registry));

        // Cart
        $this->registry->set('cart', new Cart($this->registry));

        // Encryption
        $this->registry->set('encryption', new Encryption($this->config->get('config_encryption')));

        $this->trigger->fire('post.app.ecommerce');
    }

    public function route()
    {
        // Route
        $route = new Route($this->registry);

        // Parse
        $route->parse();

        // Set
        $this->registry->set('path', $route);

        $this->trigger->fire('post.app.route');
    }

    public function dispatch()
    {
        // B/C start
        global $registry;
        $registry = $this->registry;

        global $config;
        $config = $this->registry->get('config');

        global $db;
        $db = $this->registry->get('db');

        global $log;
        $log = $this->registry->get('log');
        // B/C end

        // Front Controller
        $controller = new Front($this->registry);

        //echo "2";
        //print_r( $this->request->post);die;

        // Maintenance Mode
        $controller->addPreAction(new Action('common/maintenance'));

        // Router
        if (isset($this->request->get['path'])) {
            $action = new Action($this->request->get['path']);
        } else {
            $action = new Action('common/home');
        }

        // Dispatch
        $controller->dispatch($action, new Action('error/not_found'));

        // Set the page cache if enabled
        $this->pagecache->setPage($this->response);

        $this->trigger->fire('post.app.dispatch');
    }
}
