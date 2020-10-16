<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Models\System\User;

$routes = [
    [
        'path'          => '/',
        'type'          => 'get',
        'controller'    => 'Front\Index\IndexController',
        'method'        => 'index'
    ],
    [
        'path'          => 'page/{slug}',
        'type'          => 'get',
        'controller'    => 'Front\Page\PagesController',
        'method'        => 'show',
        'name'          => 'pages.show'
    ],
];


Route::domain(config('app.base_domain'))->group(function () use (&$routes) {

    if (filled($routes)) {
        foreach ($routes as $index => $route) {
            create_route($route['path'], $route);
        }
    }

    // Authentication Routes
    Auth::routes(['verify'=>true]);

    // Catch All Route
    Route::any('{any}', function () {
        abort(404);
    })->where('any', '.*')->middleware('force.https');
});

