<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->group('/user',function ($routes){
    $routes->get('register','User\UserView::registerform');
    $routes->post('register','User\Register::register');
    $routes->get('login','User\UserView::loginform');
    $routes->post('login','User\Register::login');
});
