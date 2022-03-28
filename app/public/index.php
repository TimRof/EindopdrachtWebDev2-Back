<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

date_default_timezone_set('Europe/Amsterdam');

require __DIR__ . '/../vendor/autoload.php';

// Create Router instance
$router = new \Bramus\Router\Router();

$router->setNamespace('Controllers');

// routes for the appointments endpoint
$router->get('/appointments', 'AppointmentController@getAll');
$router->get('/appointments/(\d+)', 'AppointmentController@getOne');
$router->post('/appointments', 'AppointmentController@create');
$router->put('/appointments/(\d+)', 'AppointmentController@update');
$router->delete('/appointments/(\d+)', 'AppointmentController@delete');

// routes for the slots endpoint
$router->get('/slots/(\d+)', 'SlotController@getSlotsByDate');

// routes for the types endpoint
$router->get('/types', 'TypeController@getAll');

// routes for the users endpoint
$router->post('/users', 'UserController@create');
$router->post('/users/login', 'LoginController@login');

// install script route
$router->get('install', 'InstallController@install');

// demo routes
$router->get('/appointments/v2', 'AppointmentController@getAllv2');
$router->get('/appointments/v3', 'AppointmentController@getAllv3');

// Run it!
$router->run();
