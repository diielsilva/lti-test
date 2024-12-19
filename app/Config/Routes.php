<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('/signup', 'SignUp::form');
$routes->post('/signup', 'SignUp::store');
