<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Halaman login
$routes->get('/', 'Auth::index');
$routes->post('/login', 'Auth::login');
$routes->get('/logout', 'Auth::logout');

// Dashboard (dilindungi filter auth)
$routes->get('/dashboard', 'Dashboard::index', ['filter' => 'auth']);

// Kategori Buku (dilindungi filter auth)
$routes->group('kategori-buku', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'KategoriBuku::index');
    $routes->post('store', 'KategoriBuku::store');
    $routes->post('update/(:num)', 'KategoriBuku::update/$1');
    $routes->post('delete/(:num)', 'KategoriBuku::delete/$1');
});

// Katalog Buku (dilindungi filter auth)
$routes->group('katalog-buku', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'KatalogBuku::index');
    $routes->post('store', 'KatalogBuku::store');
    $routes->post('update/(:num)', 'KatalogBuku::update/$1');
    $routes->post('delete/(:num)', 'KatalogBuku::delete/$1');
});

// Data Anggota (dilindungi filter auth)
$routes->group('data-anggota', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'DataAnggota::index');
    $routes->post('store', 'DataAnggota::store');
    $routes->post('update/(:num)', 'DataAnggota::update/$1');
    $routes->post('delete/(:num)', 'DataAnggota::delete/$1');
});

// Transaksi Peminjaman & Pengembalian (dilindungi filter auth)
$routes->group('peminjaman', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Peminjaman::index');
    $routes->post('store', 'Peminjaman::store');
    $routes->post('kembalikan/(:num)', 'Peminjaman::kembalikan/$1');
    $routes->post('kembalikan-semua/(:num)', 'Peminjaman::kembalikanSemua/$1');
});

// Data Petugas (dilindungi filter auth)
$routes->get('/petugas', 'Petugas::index', ['filter' => 'auth']);
$routes->post('/petugas/store', 'Petugas::store', ['filter' => 'auth']);
$routes->post('/petugas/update/(:num)', 'Petugas::update/$1', ['filter' => 'auth']);
$routes->post('/petugas/delete/(:num)', 'Petugas::delete/$1', ['filter' => 'auth']);