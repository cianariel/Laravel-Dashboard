<?php

namespace App\Listeners\Office\Subscription;

use App\Events\Office\Subscription\EventSubscriptionCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class OfficeSubscriptionCreated
{
    /**
     * Handle the event.
     *
     * @param  EventSubscriptionCreated  $event
     * @return void
     */
    public function handle(EventSubscriptionCreated $event)
    {
        if(filled($event->user) && filled($event->subscription)) {
            user_activity($event->user, "Signed up for subscription - {$event->subscription->name}", $event->subscription);
        }
    }
}
