<?php

// Error Reporting
error_reporting(E_ALL);

// Check Version
if (version_compare(PHP_VERSION, '5.3.10', '<')) {
    die('Your host needs to use PHP 5.3.10 or higher to run Shop.');
}

// Windows IIS Compatibility
if (!isset($_SERVER['DOCUMENT_ROOT'])) {
    if (isset($_SERVER['SCRIPT_FILENAME'])) {
        $_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', substr($_SERVER['SCRIPT_FILENAME'], 0, 0 - strlen($_SERVER['PHP_SELF'])));
    }
}

if (!isset($_SERVER['DOCUMENT_ROOT'])) {
    if (isset($_SERVER['PATH_TRANSLATED'])) {
        $_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', substr(str_replace('\\\\', '\\', $_SERVER['PATH_TRANSLATED']), 0, 0 - strlen($_SERVER['PHP_SELF'])));
    }
}

if (!isset($_SERVER['REQUEST_URI'])) {
    $_SERVER['REQUEST_URI'] = substr($_SERVER['PHP_SELF'], 1);

    if (isset($_SERVER['QUERY_STRING'])) {
        $_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
    }
}

if (!isset($_SERVER['HTTP_HOST'])) {
    $_SERVER['HTTP_HOST'] = getenv('HTTP_HOST');
}

// Check if SSL
if (isset($_SERVER['HTTPS']) && (('on' == $_SERVER['HTTPS']) || ('1' == $_SERVER['HTTPS']))) {
    $_SERVER['HTTPS'] = true;
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && 'https' == $_SERVER['HTTP_X_FORWARDED_PROTO'] || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && 'on' == $_SERVER['HTTP_X_FORWARDED_SSL']) {
    $_SERVER['HTTPS'] = true;
} else {
    $_SERVER['HTTPS'] = false;
}

// Composer
if (!file_exists(DIR_SYSTEM . 'vendor/autoload.php')) {
    die('You need to run Composer first. More details https://github.com/shop/shop');
}
require_once DIR_SYSTEM . 'vendor/autoload.php';

// Modification Override
function modification($filename) {
    if (!defined('DIR_CATALOG')) {
        $file = DIR_MODIFICATION . 'catalog/' . substr($filename, strlen(DIR_APPLICATION));
    } else {
        $file = DIR_MODIFICATION . 'admin/' . substr($filename, strlen(DIR_APPLICATION));
    }

    if (DIR_SYSTEM == substr($filename, 0, strlen(DIR_SYSTEM))) {
        $file = DIR_MODIFICATION . 'system/' . substr($filename, strlen(DIR_SYSTEM));
    }

    if (is_file($file)) {
        return $file;
    }

    return $filename;
}

// Autoloader
function autoload($class) {
    $lib = DIR_SYSTEM . 'library/' . str_replace('\\', '/', strtolower($class)) . '.php';
    $app = DIR_SYSTEM . 'library/app/' . str_replace('\\', '/', strtolower($class)) . '.php';

    if (is_file($lib)) {
        include modification($lib);

        return true;
    } elseif (is_file($app)) {
        include modification($app);

        return true;
    }

    return false;
}

spl_autoload_register('autoload');
spl_autoload_extensions('.php');

// Engine
require_once modification(DIR_SYSTEM . 'engine/action.php');
require_once modification(DIR_SYSTEM . 'engine/controller.php');
require_once modification(DIR_SYSTEM . 'engine/event.php');
require_once modification(DIR_SYSTEM . 'engine/front.php');
require_once modification(DIR_SYSTEM . 'engine/loader.php');
require_once modification(DIR_SYSTEM . 'engine/model.php');
require_once modification(DIR_SYSTEM . 'engine/registry.php');

// Helper
require_once DIR_SYSTEM . 'helper/json.php';
require_once DIR_SYSTEM . 'helper/utf8.php';

require_once 'define.php';
require_once DIR_ADMIN . 'newdefine.php';
//$_SERVER['REQUEST_URI'] = '/';
