<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Illuminate\Auth\Events\Registered' => [
            'Illuminate\Auth\Listeners\SendEmailVerificationNotification'
        ],
        'Illuminate\Auth\Events\Verified' => [
            'App\Listeners\LogVerifiedUser',
        ],
        // Office Events.
        'App\Events\Office\Staff\InviteUser' => [
            'App\Listeners\Office\Staff\SendStaffInvite'
        ],
        'App\Events\Office\Subscription\EventSubscriptionCreated' => [
            'App\Listeners\Office\Subscription\OfficeSubscriptionCreated'
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
