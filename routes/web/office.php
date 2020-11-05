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
    [
        'path'          => 'setup/subscription',
        'type'          => 'get',
        'controller'    => 'Office\Setup\SetupController',
        'method'        => 'selectSubscription',
        'name'          => 'setup.account.subscription.signup'
    ],
    [
        'path'          => 'setup/payment/thankyou',
        'type'          => 'get',
        'controller'    => 'Office\Setup\SetupController',
        'method'        => 'thankyou',
        'name'          => 'office.setup.complete'
    ],
    [
        'path'          => 'setup/subscription',
        'type'          => 'post',
        'controller'    => 'Office\Setup\SetupController',
        'method'        => 'createSubscription',
        'name'          => 'setup.account.subscription.store'
    ],
    [
        'path'          => 'settings',
        'type'          => 'get',
        'controller'    => 'Office\Setting\SettingsController',
        'method'        => 'edit',
        'name'          => 'settings.edit'
    ],
    [
        'path'          => 'settings',
        'type'          => 'put',
        'controller'    => 'Office\Setting\SettingsController',
        'method'        => 'update',
        'name'          => 'settings.update'
    ],
    [
        'path'          => 'staff',
        'type'          => 'resource',
        'controller'    => 'Office\Staff\StaffsController',
        'except'        => ['show']
    ]
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