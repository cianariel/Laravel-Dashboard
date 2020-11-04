<?php

namespace App\Events\Office\Staff;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\System\User;

class InviteUser
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var App\Models\System\User $owner
     */
    public $owner;
    /**
     * @var App\Models\System\User $guestUser
     */
    public $guestUser;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $owner, User $guestUser)
    {
        $this->owner = $owner;
        $this->guestUser = $guestUser;
    }
}
