<?php

/*ini_set('date.timezone','UTC');*/

if (version_compare(PHP_VERSION, '5.3.10', '<')) {
    die('Your host needs to use PHP 5.3.10 or higher to run Multi Vendor Grocery.');
}

define('MVG', 1);

require_once 'define.php';
require_once 'newdefine.php';

// Startup
require_once DIR_SYSTEM.'startup.php';

date_default_timezone_set('Africa/Nairobi');

/*$registry = new Registry();
// Config
$config = new Config();
$registry->set('config', $config);

$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$registry->set('db', $db);


if (!ini_get('date.timezone') || true) {

    $timezone = $db->query( "SELECT value FROM " . DB_PREFIX . "setting where `key`='config_timezone'");

    if($timezone->num_rows) {
        $timezone = $timezone->row['value'];
        date_default_timezone_set($timezone);
    } else {
        date_default_timezone_set('Asia/Kolkata');
    }
}

echo "<pre>";print_r("vf");die;*/
// App
$app = new Admin();

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
