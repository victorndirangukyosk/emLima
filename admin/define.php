<?php

defined('MVG') or die;

// Version
define('VERSION', '2.0.0');

// HTTP
define('HTTP_SERVER', 'http://'.$_SERVER['HTTP_HOST'].rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/.\\').'/admin/');
define('HTTP_CATALOG', 'http://'.$_SERVER['HTTP_HOST'].rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/.\\').'/');
define('HTTP_IMAGE', 'http://'.$_SERVER['HTTP_HOST'].rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/.\\').'/image/');
define('HTTP_ADMIN', 'http://'.$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['SCRIPT_NAME']), '/.\\').'/admin/');

define('NEW_HTTP_ADMIN', 'http://'.$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['SCRIPT_NAME']), '/.\\'));

// HTTPS
define('HTTPS_SERVER', (!empty($_SERVER['HTTPS']) ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/.\\').'/admin/');
define('HTTPS_CATALOG', (!empty($_SERVER['HTTPS']) ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/.\\').'/');
define('HTTPS_IMAGE', (!empty($_SERVER['HTTPS']) ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/.\\').'/image/');
define('HTTPS_ADMIN', (!empty($_SERVER['HTTPS']) ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['SCRIPT_NAME']), '/.\\').'/admin/');

define('NEW_HTTPS_ADMIN', (!empty($_SERVER['HTTPS']) ? 'https://' : 'http://').$_SERVER['HTTP_HOST'].rtrim(dirname($_SERVER['SCRIPT_NAME']), '/.\\'));

// Variables
define('IS_ADMIN', true);

// Directories
$base = __DIR__;

$parts = explode(DIRECTORY_SEPARATOR, $base);
array_pop($parts);

define('DIR_BASE', $base.'/');
define('DIR_ROOT', implode(DIRECTORY_SEPARATOR, $parts).'/');
define('DIR_INSTALL', DIR_ROOT.'install/');
define('DIR_APPLICATION', DIR_ROOT.'admin/');
define('DIR_SYSTEM', DIR_ROOT.'system/');
define('DIR_CATALOG', DIR_ROOT.'front/');
define('DIR_VQMOD', DIR_ROOT.'vqmod/');
define('DIR_IMAGE', DIR_ROOT.'image/');
define('DIR_DOWNLOAD', DIR_ROOT.'download/');
define('DIR_UPLOAD', DIR_ROOT.'upload/');
define('DIR_CONFIG', DIR_SYSTEM.'config/');
define('DIR_CACHE', DIR_SYSTEM.'cache/');
define('DIR_LOG', DIR_SYSTEM.'log/');
define('DIR_LOGS', DIR_SYSTEM.'log/'); // depreciated due to plural usage, use DIR_LOG
define('DIR_MODIFICATION', DIR_SYSTEM.'modification/');
define('DIR_LANGUAGE', DIR_APPLICATION.'language/');
define('DIR_TEMPLATE', DIR_APPLICATION.'ui/template/');
define('BUCKET_PREFIX', 'local-');


// Installation check, and check on removal of the install directory.
if (!file_exists(DIR_ROOT.'config.php') or (filesize(DIR_ROOT.'config.php') < 10) or file_exists(DIR_INSTALL.'index.php')) {
    if (file_exists(DIR_INSTALL.'index.php')) {
        header('Location: ../install/index.php');

        exit();
    } else {
        echo 'No configuration file found and no installation code available. Exiting...';

        exit();
    }
}
