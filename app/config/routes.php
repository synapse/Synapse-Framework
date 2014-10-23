<?php defined('_INIT') or die;

$router = $app->getRouter();

// Index
$router->get('/', 'Main');
$router->module('/translate', 'translate');