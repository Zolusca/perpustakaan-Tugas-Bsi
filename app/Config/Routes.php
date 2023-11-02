<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->group('/user',function ($routes){
    $routes->get('register','UserController::getRegisterForm');
    $routes->post('register','User\Register::register');
    $routes->get('login','UserController::getLoginForm');
    $routes->post('login','User\Login::login');
});

$routes->group('user/dashboard',function ($routes){
    $routes->get
    (
        'buku/detail/(:segment)',
        'Dashboard\Member\DashboardMember::tampilanDetailBuku/$1'
    );
    $routes->get
    (
        'buku',
        'Dashboard\Member\DashboardMember::tampilanDashboardHome',
        ['filter'=>'authorization']
    );
    $routes->get
    (
        'buku/booking/list',
        'Dashboard\Member\DashboardMember::tampilanListDataBookingUser',
        ['filter'=>'authorization']
    );
    $routes->get
    (
        'buku/booking/delete/(:any)',
        'Dashboard\Member\DashboardMember::userDeleteDataBookingTemporary/$1',
        ['filter'=>'authorization']
    );
    $routes->get
    (
        'buku/booking/pdf',
        'Dashboard\Member\DashboardMember::prosesTemporayBookKeBookingDanDetailBooking',
        ['filter'=>'authorization']
    );
    $routes->post
    (
        'buku/booking',
        'Dashboard\Member\DashboardMember::handlerUserBookingBuku',
        ['filter'=>'authorization']
    );
});

$routes->group('admin/dashboard',['filter'=>'authorization'],function ($routes){
    $routes->get
    (
        'main',
        'Dashboard\Admin\DashboardAdmin::tampilanAdminBuku'
    );
    $routes->get
    (
        'userbooking',
        'Dashboard\Admin\DashboardAdmin::tampilanAdminBooking'
    );
    $routes->get
    (
        'userpeminjam',
        'Dashboard\Admin\DashboardAdmin::tampilanAdminPeminjam'
    );
    $routes->get
    (
        'userlist',
        'Dashboard\Admin\DashboardAdmin::adminAksiLihatdaftarAnggota'
    );
    $routes->get
    (
        'laporanlistuser',
        'Dashboard\Admin\LaporanAdmin::laporanListUser'
    );
    $routes->get
    (
        'laporanpeminjam',
        'Dashboard\Admin\LaporanAdmin::laporanListUserPeminjam'
    );
    $routes->post
    (
        'tambahbuku',
        'Dashboard\Admin\DashboardAdmin::adminTambahBukuAction'
    );
    $routes->post
    (
        'caribooking',
        'Dashboard\Admin\DashboardAdmin::adminSeacrhUserBooking'
    );
    $routes->post
    (
        'userambilbuku',
        'Dashboard\Admin\DashboardAdmin::adminAksiUserBookingToPinjam'
    );
    $routes->post
    (
        'caripeminjam',
        'Dashboard\Admin\DashboardAdmin::adminSearchUserPeminjam'
    );
    $routes->post
    (
        'userkembalikanbuku',
        'Dashboard\Admin\DashboardAdmin::adminAksiUserKembalikanPinjam'
    );
});

$routes->get('/','UserController::home');
//$routes->get('/','UserController::test');


