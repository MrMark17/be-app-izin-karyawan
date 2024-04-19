<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

service('auth')->routes($routes);

$routes->group('api', function ($routes) {
    $routes->group('', ['namespace' => 'App\Controllers\Auth'], static function ($routes) {
        $routes->post('register', 'RegisterController::index');
        $routes->post('login', 'LoginController::index');
        $routes->post('logout', 'LogoutController::index');
    });
    $routes->get('employees', 'EmployeeController::index');
    $routes->get('employees/(:num)', 'EmployeeController::detail/$1');
    $routes->put('employees/(:num)/update', 'EmployeeController::update/$1');
});
