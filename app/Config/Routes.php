<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('home', 'Home::index');
$routes->get('/message', 'Home::message');
$routes->get('/fileMessage', 'Home::fileMessage');
$routes->get('/receiveMessage', 'Home::receiveMessage');
$routes->get('/download', 'Home::downloadFile');
$routes->post('/submitM', 'Home::submitMessage');
$routes->post('/submitF', 'Home::submitFiles');
$routes->delete('/cleanup', 'Home::cleanup');
