<?php

defined('MVG') or die;
define('ACTIVE_STORE_ID', '2');
// Version
define('VERSION', '2.0.0');
define('BASE_URL','https://www.emlima.com');
define('DELEVERY_GENERATE_STATUS',3);
// HTTP
define('HTTP_SERVER', 'http://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/.\\') . '/');
define('HTTP_IMAGE', 'http://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/.\\') . '/image/');
define('HTTP_ADMIN', 'http://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/.\\') . '/admin/');

define('HTTPS_CATALOG', (!empty($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/.\\') . '/');

// HTTPS
define('HTTPS_SERVER', (!empty($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/.\\') . '/');
define('HTTPS_IMAGE', (!empty($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/.\\') . '/image/');
define('HTTPS_ADMIN', (!empty($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/.\\') . '/admin/');

// Variables
define('IS_ADMIN', false);

// Directories
$base = __DIR__;

define('DIR_BASE', $base . '/');
define('DIR_ROOT', $base . '/');


//define('const_latitude', '-1.299392');
//define('const_longitude', '36.7818779');
//define('const_location_name', 'Nairobi, Kenya');

/*define('const_latitude', '');
define('const_longitude', '');
define('const_location_name', '');
*/

define('DIR_INSTALL', DIR_ROOT . 'install/');
define('DIR_APPLICATION', DIR_ROOT . 'front/');
define('DIR_SYSTEM', DIR_ROOT . 'system/');
define('DIR_ADMIN', DIR_ROOT . 'admin/');
define('DIR_VQMOD', DIR_ROOT . 'vqmod/');
define('DIR_IMAGE', DIR_ROOT . 'image/');
define('DIR_DOWNLOAD', DIR_ROOT . 'download/');
define('DIR_UPLOAD', DIR_ROOT . 'upload/');
define('DIR_CONFIG', DIR_SYSTEM . 'config/');
define('DIR_CACHE', DIR_SYSTEM . 'cache/');
define('DIR_MODIFICATION', DIR_SYSTEM . 'modification/');
define('DIR_LOG', DIR_SYSTEM . 'log/');
define('DIR_LOGS', DIR_SYSTEM . 'log/'); // depreciated due to plural usage, use DIR_LOG
define('DIR_LANGUAGE', DIR_APPLICATION . 'language/');
define('DIR_TEMPLATE', DIR_APPLICATION . 'ui/theme/');

// Installation check, and check on removal of the install directory.
if (!file_exists(DIR_ROOT . 'config.php') or ( filesize(DIR_ROOT . 'config.php') < 10) or file_exists(DIR_INSTALL . 'index.php')) {
    if (file_exists(DIR_INSTALL . 'index.php')) {
        header('Location: ' . substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], 'index.php')) . 'install/index.php');

        exit();
    } else {
        
        echo 'No configuration file found and no installation code available. Exiting...';

        exit();
    }
}
