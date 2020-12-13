<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| User Routes
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
        'controller'    => 'User\UserController',
        'method'        => 'index',
        'name'          => 'dashboard'
    ],
    [
        'path'          => 'profile/edit',
        'type'          => 'get',
        'controller'    => 'User\Profile\ProfileController',
        'method'        => 'edit',
        'name'          => 'profile.edit'
    ],
    [
        'path'          => 'profile/edit/update',
        'type'          => 'PUT',
        'controller'    => 'User\Profile\ProfileController',
        'method'        => 'update',
        'name'          => 'profile.update'
    ],
];

Route::domain(config('app.base_domain'))->name('user.')->group(function () use (&$routes) {
    Route::prefix('user')->group(function () use (&$routes) {

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