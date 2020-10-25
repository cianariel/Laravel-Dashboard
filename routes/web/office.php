<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Office Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


$routes = [
    [
        'path'          => '/',
        'type'          => 'get',
        'controller'    => 'Office\OfficeController',
        'method'        => 'index',
        'name'          => 'dashboard'
    ],
    [
        'path'          => 'setup',
        'type'          => 'get',
        'controller'    => 'Office\Setup\SetupController',
        'method'        => 'index',
        'name'          => 'setup.account'
    ],
    [
        'path'          => 'setup',
        'type'          => 'post',
        'controller'    => 'Office\Setup\SetupController',
        'method'        => 'saveOfficeProfile',
        'name'          => 'setup.account.office.store'
    ],
];

Route::domain(config('app.base_domain'))->name('office.')->group(function () use (&$routes) {
    Route::prefix('office')->group(function () use (&$routes) {

        if (filled($routes)) {
            foreach ($routes as $index => $route) {
                 create_route($route['path'], $route);
            }
        }

        // Catch All Route
        Route::any('{any}', function () {
            abort(404);
        })->where('any', '.*')->middleware('force.https');
    });
});