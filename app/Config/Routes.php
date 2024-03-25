<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/home', 'Home::index');
$routes->get('/profile', 'Home::profile');
$routes->get('/message', 'Home::message');
$routes->get('/fileMessage', 'Home::fileMessage');
$routes->get('/receiveMessage', 'Home::receiveMessage');
$routes->post('/submitM', 'Home::submitMessage');
$routes->post('/submitF', 'Home::submitFiles');
$routes->delete('/cleanup', 'Home::cleanup');
$routes->get('download/(:any)', 'DownloadController::download/$1');
$routes->get('/processEmails', 'Home::processEmails');
$routes->match(['get', 'post'], '/login', 'Home::login');
$routes->match(['get', 'post'], '/signup', 'Home::signup');
$routes->post('/logout', 'Home::logout');