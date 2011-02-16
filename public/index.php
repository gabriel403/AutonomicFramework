<?php

// Define path to root directory
defined('ROOT_PATH')
        || define('ROOT_PATH', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..'));

// Define path to application directory
defined('APP_PATH')
        || define('APP_PATH', realpath(ROOT_PATH . DIRECTORY_SEPARATOR . 'apps'));


// Define application environment
defined('APP_ENV')
        || define('APP_ENV', (getenv('APP_ENV') ? getenv('APP_ENV') : 'production'));

if( APP_ENV != "production" ) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
}

// Add libs to php path
set_include_path(
        implode(
                PATH_SEPARATOR, array(
            realpath(ROOT_PATH . DIRECTORY_SEPARATOR . 'libs'),
            APP_PATH,
            get_include_path()
                )
        )
);
require_once 'Autonomic' . DIRECTORY_SEPARATOR . 'Bootstrap.php';
$ab = new Autonomic_Bootstrap();
$ab->run();

