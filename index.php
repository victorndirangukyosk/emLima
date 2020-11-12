<?php

//echo phpinfo();exit;
if (version_compare(PHP_VERSION, '5.3.10', '<')) {
    die('Your host needs to use PHP 5.3.10 or higher to run Multi Vendor Grocery.');
}

define('MVG', 1);

require_once 'define.php';

if (is_file('config.php')) {
    require_once 'config.php';
}

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

define('HTTP_CATALOG', 'http://'.$_SERVER['HTTP_HOST'].rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/.\\').'/');

// Startup
require_once DIR_SYSTEM.'startup.php';

$registry = new Registry();

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Config
$config = new Config();
$registry->set('config', $config);

// Database
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$registry->set('db', $db);

if (!ini_get('date.timezone') || true) {
    $timezone = $db->query('SELECT value FROM '.DB_PREFIX."setting where `key`='config_timezone'");

    if ($timezone->num_rows) {
        $timezone = $timezone->row['value'];
        date_default_timezone_set($timezone);
    } else {
        date_default_timezone_set('Asia/Kolkata');
    }
}

date_default_timezone_set('Africa/Nairobi');

/*ini_set('date.timezone','UTC');
$db->query("SET time_zone = '" . date('P') . "'"); */

// Request
$request = new Request();
$registry->set('request', $request);

// Response
$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$response->setCompression($config->get('config_compression'));
$registry->set('response', $response);

// Session
/*if (isset($request->get['token']) && isset($request->get['route']) && substr($request->get['route'], 0, 4) == 'api/') {
    $db->query("DELETE FROM `" . DB_PREFIX . "api_session` WHERE TIMESTAMPADD(HOUR, 1, date_modified) < NOW()");

    $query = $db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "api` `a` LEFT JOIN `" . DB_PREFIX . "api_session` `as` ON (a.api_id = as.api_id) LEFT JOIN " . DB_PREFIX . "api_ip `ai` ON (as.api_id = ai.api_id) WHERE a.status = '1' AND as.token = '" . $db->escape($request->get['token']) . "' AND ai.ip = '" . $db->escape($request->server['REMOTE_ADDR']) . "'");

    if ($query->num_rows) {
        // Does not seem PHP is able to handle sessions as objects properly so so wrote my own class
        $session = new Session($query->row['session_id'], $query->row['session_name']);
        $registry->set('session', $session);

        // keep the session alive
        $db->query("UPDATE `" . DB_PREFIX . "api_session` SET date_modified = NOW() WHERE api_session_id = '" . $query->row['api_session_id'] . "'");
    }
} else {
    $session = new Session();
    $registry->set('session', $session);
}*/
/*// Cache
$cache = new Cache('file');

$registry->set('cache', $cache);
// Session
$session = new Session();
 if (isset($request->get['token']) && isset($request->get['route']) && substr($request->get['route'], 0, 4) == 'api/') {
    $db->query("DELETE FROM `" . DB_PREFIX . "api_session` WHERE TIMESTAMPADD(HOUR, 1, date_modified) < NOW()");

    $query = $db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "api` `a` LEFT JOIN `" . DB_PREFIX . "api_session` `as` ON (a.api_id = as.api_id) LEFT JOIN " . DB_PREFIX . "api_ip `ai` ON (as.api_id = ai.api_id) WHERE a.status = '1' AND as.token = '" . $db->escape($request->get['token']) . "' AND ai.ip = '" . $db->escape($request->server['REMOTE_ADDR']) . "'");

    if ($query->num_rows) {
        $session->start($query->row['session_id'], $query->row['session_name']);
        $registry->set('session', $session);

        // keep the session alive
        $db->query("UPDATE `" . DB_PREFIX . "api_session` SET date_modified = NOW() WHERE api_session_id = '" . (int)$query->row['api_session_id'] . "'");
    }
 } else {
    $session->start();
 }
$registry->set('session', $session);*/

// App
$app = new Catalog();

// Initialise main classes
$app->initialise();

// Load eCommerce classes
$app->ecommerce();

// Route the app
$app->route();

// Dispatch the app
$app->dispatch();

// Render the output
$app->render();
