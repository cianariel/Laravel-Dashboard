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
        'path'          => 'setup/subscription',
        'type'          => 'post',
        'controller'    => 'Office\Setup\SetupController',
        'method'        => 'createSubscription',
        'name'          => 'setup.account.subscription.store'
    ],
    [
        'path'          => 'setup/complete/thankyou',
        'type'          => 'get',
        'controller'    => 'Office\Setup\SetupController',
        'method'        => 'thankyou',
        'name'          => 'setup.complete'
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
        'path'          => 'settings/rep/visits/{view}',
        'type'          => 'get',
        'controller'    => 'Office\Setting\SettingsController',
        'method'        => 'editRepVisitsSettings',
        'name'          => 'settings.edit.rep.visits.view'
    ],
    [
        'path'          => 'settings/general',
        'type'          => 'get',
        'controller'    => 'Office\Setting\SettingsController',
        'method'        => 'editGeneralSettings',
        'name'          => 'settings.edit.general'
    ],
    [
        'path'          => 'settings/general/{section}',
        'type'          => 'get',
        'controller'    => 'Office\Setting\SettingsController',
        'method'        => 'editGeneralSettings',
        'name'          => 'settings.edit.general.section'
    ],
    [
        'path'          => 'settings/general/{section}',
        'type'          => 'put',
        'controller'    => 'Office\Setting\SettingsController',
        'method'        => 'updateGeneralSettings',
        'name'          => 'settings.update.general.section'
    ],
    [
        'path'          => 'settings/general/recurring_appointments/create',
        'type'          => 'post',
        'controller'    => 'Office\Setting\SettingsController',
        'method'        => 'createRecurringCalendarEvent',
        'name'          => 'settings.create.recurring.appointment'
    ],
    [
        'path'          => 'settings/general/recurring_appointments/{id}/destroy',
        'type'          => 'delete',
        'controller'    => 'Office\Setting\SettingsController',
        'method'        => 'deleteRecurringAppointment',
        'name'          => 'settings.create.recurring.appointment.destroy'
    ],
    [
        'path'          => 'settings/offices',
        'type'          => 'get',
        'controller'    => 'Office\Setting\SettingsController',
        'method'        => 'editOfficesSettings',
        'name'          => 'settings.edit.offices'
    ],
    [
        'path'          => 'settings/calendar',
        'type'          => 'get',
        'controller'    => 'Office\Setting\SettingsController',
        'method'        => 'editCalendarSettings',
        'name'          => 'settings.edit.calendar'
    ],
    [
        'path'          => 'settings/subscription',
        'type'          => 'get',
        'controller'    => 'Office\Setting\SettingsController',
        'method'        => 'editSubscriptionSettings',
        'name'          => 'settings.edit.subscription'
    ],
    [
        'path'          => 'staff',
        'type'          => 'resource',
        'controller'    => 'Office\Staff\StaffsController',
        'except'        => ['show']
    ],
    [
        'path'          => 'calendar',
        'type'          => 'resource',
        'controller'    => 'Office\Calendar\CalendarController',
    ],
    [
        'path'          => 'messages',
        'type'          => 'resource',
        'controller'    => 'Office\Message\MessagesController',
        'except'        => ['edit', 'update']
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