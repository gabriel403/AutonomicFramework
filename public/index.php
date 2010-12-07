<?php
// Define path to root directory
defined('ROOT_PATH')
        || define('ROOT_PATH', realpath(dirname(__FILE__) . '/..'));

// Define path to application directory
defined('APP_PATH')
        || define('APP_PATH', realpath(ROOT_PATH . '/apps'));


// Define application environment
defined('APP_ENV')
        || define('APP_ENV', (getenv('APP_ENV') ? getenv('APP_ENV') : 'production'));

// Add libs to php path
set_include_path(
    implode(
        PATH_SEPARATOR,
        array(
            realpath(ROOT_PATH . '/libs'),
            APP_PATH,
            get_include_path()
        )
    )
);
require_once 'Autonomic/Bootstrap.php';
$ab = new Autonomic_Bootstrap();
$ab->run();

