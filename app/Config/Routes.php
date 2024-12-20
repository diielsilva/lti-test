<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'SignIn::index');
$routes->post('/signin', 'SignIn::authenticate');

$routes->get('/signup', 'SignUp::form');
$routes->post('/signup', 'SignUp::store');

$routes->group('/users', static function ($routes) {
    $routes->get('', 'SignIn::home');
    $routes->put('', 'Users::update');
    $routes->delete('', 'User::delete');
});

$routes->put('/users', 'User::update');
$routes->delete('/users', 'User::delete');

$routes->get('/categories', 'Category::index');
$routes->post('/categories', 'Category::create');
$routes->get('/categories/all', 'Category::findAllByUser');
$routes->delete('/categories', 'Category::delete');
$routes->put('/categories', 'Category::update');

$routes->get('/spents', 'Spent::index');
$routes->post('/spents', 'Spent::create');
$routes->get('/spents/by-user', 'Spent::findByUser');

$routes->post('/logout', 'User::logout');
