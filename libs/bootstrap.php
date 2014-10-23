<?php

/**
 * @package     Synapse
 * @subpackage  Bootstrap
 */

defined('_INIT') or die;

if(DEBUG){
	ini_set('display_errors', 1);
	ini_set('log_errors', 1);
	ini_set('error_log', LOGS . '/log.txt');
	error_reporting(E_ALL & ~E_STRICT);
}

// include the config file
require_once(CONFIG);
// include the custom functions file
include(LIBRARY.'/functions.php');
// include the autoloader class
include(LIBRARY.'/loader.php');
// register the class autoloader
Loader::register();
// get the first app instance
$app = App::getInstance();
// include the routes
include(ROUTES);
// run the application
$app->run();