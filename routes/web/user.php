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
    [
        'path'          => 'profile/edit/media/delete',
        'type'          => 'get',
        'controller'    => 'User\Profile\ProfileController',
        'method'        => 'deleteMediaImage',
        'name'          => 'profile.media.delete'
    ],
    [
        'path'          => 'setup',
        'type'          => 'get',
        'controller'    => 'User\Setup\SetupController',
        'method'        => 'index',
        'name'          => 'setup.account'
    ],
    [
        'path'          => 'setup',
        'type'          => 'post',
        'controller'    => 'User\Setup\SetupController',
        'method'        => 'saveUserProfile',
        'name'          => 'setup.account.profile.store'
    ],
    [
        'path'          => 'setup/update/status',
        'type'          => 'post',
        'controller'    => 'User\Setup\SetupController',
        'method'        => 'updateUserStatus',
        'name'          => 'setup.account.status'
    ],
    [
        'path'          => 'setup/change/password',
        'type'          => 'post',
        'controller'    => 'User\Setup\SetupController',
        'method'        => 'changeUserPassword',
        'name'          => 'setup.account.password.change'
    ],
    [
        'path'          => 'setup/subscription',
        'type'          => 'get',
        'controller'    => 'User\Setup\SetupController',
        'method'        => 'selectSubscription',
        'name'          => 'setup.account.subscription.signup'
    ],
    [
        'path'          => 'setup/subscription',
        'type'          => 'post',
        'controller'    => 'User\Setup\SetupController',
        'method'        => 'createSubscription',
        'name'          => 'setup.account.subscription.store'
    ],
    [
        'path'          => 'setup/complete/thankyou',
        'type'          => 'get',
        'controller'    => 'User\Setup\SetupController',
        'method'        => 'thankyou',
        'name'          => 'setup.complete'
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