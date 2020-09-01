<?php

use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

class Admin extends App
{
    public function initialise()
    {
        // File System
        $this->registry->set('filesystem', new Filesystem());

        // Config
        $this->registry->set('config', new Config());

        // Database
        $this->registry->set('db', new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE));

        // Store
        $this->config->set('config_store_id', 0);

        // Settings
        $query = $this->db->query('SELECT * FROM `'.DB_PREFIX."setting` WHERE store_id = '0' OR store_id = '".(int) $this->config->get('config_store_id')."' ORDER BY store_id ASC");

        foreach ($query->rows as $setting) {
            if (!$setting['serialized']) {
                $this->config->set($setting['key'], $setting['value']);
            } else {
                $this->config->set($setting['key'], unserialize($setting['value']));
            }
        }

        // Loader
        $this->registry->set('load', new Loader($this->registry));

        // Trigger
        $this->registry->set('trigger', new Trigger($this->registry));

        // Url
        $this->registry->set('url', new Url(HTTP_SERVER, HTTPS_SERVER, $this->registry));

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
        $this->registry->set('response', $response);

        // Cache
        $cache = new Cache($this->config->get('config_cache_storage', 'file'), $this->config->get('config_cache_lifetime', 86400));
        $this->registry->set('cache', $cache);

        // Session
        $this->registry->set('session', new Session());

        // Language
        $languages = [];

        $query = $this->db->query('SELECT * FROM `'.DB_PREFIX.'language`');

        foreach ($query->rows as $result) {
            $languages[$result['code']] = $result;
        }

        if (isset($this->session->data['language']) && array_key_exists($this->session->data['language'], $languages) && $languages[$this->session->data['language']]['status']) {
            $code = $this->session->data['language'];
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

            $code = $detect ? $detect : $this->config->get('config_admin_language');
        }

        if (!isset($this->session->data['language']) || $this->session->data['language'] != $code) {
            $this->session->data['language'] = $code;
        }

        $this->config->set('config_language_id', $languages[$code]['language_id']);
        //$this->config->set('config_language', $languages[$code]['code']);

        // Language
        $language = new Language($languages[$code]['directory'], $this->registry);
        $language->load('english');
        $language->load($languages[$code]['directory']);
        $this->registry->set('language', $language);

        // Document
        $this->registry->set('document', new Document());

        // Utility
        $this->registry->set('utility', new Utility($this->registry));

        // Update
        $this->registry->set('update', new Update($this->registry));

        $this->trigger->fire('post.app.initialise');
    }

    public function ecommerce()
    {
        // Cart
        $this->registry->set('cart', new Cart($this->registry));

        // Currency
        $this->registry->set('currency', new Currency($this->registry));

        // Email Template
        $this->registry->set('emailtemplate', new Emailtemplate($this->registry));

        // Weight
        $this->registry->set('weight', new Weight($this->registry));

        // Length
        $this->registry->set('length', new Length($this->registry));

        // User
        $this->registry->set('user', new User($this->registry));

        // Tax
        $this->registry->set('tax', new Tax($this->registry));

        $this->trigger->fire('post.app.ecommerce');
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

        // Login
        $controller->addPreAction(new Action('common/login/check'));

        // Permission
        $controller->addPreAction(new Action('error/permission/check'));

        // Router
        if (isset($this->request->get['path'])) {
            $action = new Action($this->request->get['path']);
        } else {
            $action = new Action('common/dashboard');
        }

        // Dispatch
        $controller->dispatch($action, new Action('error/not_found'));

        $this->trigger->fire('post.app.dispatch');
    }
}
