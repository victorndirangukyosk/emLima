<?php

/*define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'goldtree9');
define('DB_DATABASE', 'www.emlima.com');
define('DB_PREFIX', 'hf7_');
 */

define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', getenv('MYSQL_HOST') ? getenv('MYSQL_HOST') : 'localhost');
define('DB_USERNAME', getenv('MYSQL_USERNAME') ? getenv('MYSQL_USERNAME') : 'root');
define('DB_PASSWORD', getenv('MYSQL_ROOT_PASSWORD') ? getenv('MYSQL_ROOT_PASSWORD') : '');
define('DB_DATABASE', getenv('MYSQL_ROOT_PASSWORD') ? basename(__DIR__) : 'kwikbasket');
define('DB_PREFIX', 'hf7_');

// Africa's Talking BulkSMS
define('AT_USERNAME', 'kwikbasket');
define('AT_API_KEY', 'c9b0e266179d19ad024476d4c71256cfa1b127c0d26b44e79c6e8438ccc10592');