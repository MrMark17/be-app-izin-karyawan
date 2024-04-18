<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

service('auth')->routes($routes);

$routes->group('api', function ($routes) {
    $routes->group('', ['namespace' => 'App\Controllers\Auth'], static function ($routes) {
        $routes->post('register', 'RegisterController::index');
        $routes->post('login', 'LoginController::index');
        $routes->post('logout', 'LogoutController::index');
    });
});
