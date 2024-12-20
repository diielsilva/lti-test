<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'SignIn::index');
$routes->post('/signin', 'SignIn::authenticate');

$routes->get('/signup', 'SignUp::form');
$routes->post('/signup', 'SignUp::store');

$routes->get('/home', 'SignIn::home');

$routes->put('/users', 'User::update');

$routes->post('/logout', 'User::logout');
