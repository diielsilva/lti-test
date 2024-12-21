<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');

$routes->group('/login', static function ($routes) {
    $routes->get('', 'Login::index');
    $routes->post('', 'Login::authenticate');
});

$routes->get('/signup', 'SignUp::form');
$routes->post('/signup', 'SignUp::store');

$routes->group('/users', static function ($routes) {
    $routes->get('', 'User::index');
    $routes->put('', 'User::update');
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
