<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Halaman login
$routes->get('/', 'Auth::index');
$routes->post('/login', 'Auth::login');
$routes->get('/logout', 'Auth::logout');

// Dashboard (dilindungi filter auth, hanya bisa diakses jika sudah login)
$routes->get('/dashboard', 'Dashboard::index', ['filter' => 'auth']);
