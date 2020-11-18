<?php

namespace App\Listeners\Office\Staff;

use App\Events\Office\Staff\InviteUser;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\Office\Staff\InviteNotifcation;

class SendStaffInvite
{
    /**
     * Handle the event.
     *
     * @param  InviteUser $event
     * @return void
     */
    public function handle(InviteUser $event)
    {
        if(filled($event->owner) && filled($event->guestUser)) {

            user_activity($event->owner, "Invited staff member: {$event->guestUser->email}.");

            try {
                $event->guestUser->notify(new InviteNotifcation($event->owner, $event->guestUser));
            } catch(Exception $e) {
                logger("[App\Listeners\Office\Staff\SendStaffInvite] failed to send staff invite notification.");
            }
        }
    }
}
