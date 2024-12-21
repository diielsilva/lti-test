<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');

//Login routes
$routes->group('/login', static function ($routes) {
    $routes->get('', 'Login::index');
    $routes->post('', 'Login::authenticate');
});

//Register routes (creates an user)
$routes->group('/register', static function ($routes) {
    $routes->get('', 'Register::index');
    $routes->post('', 'Register::create');
});

//Users routes
$routes->group('/users', static function ($routes) {
    $routes->get('', 'User::index');
    $routes->put('', 'User::update');
    $routes->delete('', 'User::delete');
});

//Categories routes
$routes->group('/categories', static function ($routes) {
    $routes->get('', 'Category::index');
    $routes->get('by-user', 'Category::findByUser');
    $routes->post('', 'Category::create');
    $routes->put('', 'Category::update');
    $routes->delete('', 'Category::delete');
});

$routes->get('/spents', 'Spent::index');
$routes->post('/spents', 'Spent::create');
$routes->get('/spents/by-user', 'Spent::findByUser');
$routes->put('/spents', 'Spent::update');
$routes->delete('/spents', 'Spent::delete');

$routes->get('/reports', 'Report::index');
$routes->post('/reports', 'Report::create');

$routes->post('/logout', 'User::logout');
