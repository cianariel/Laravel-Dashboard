<?php

namespace App\Events\Office\Subscription;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\System\User;

class EventSubscriptionCreated
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;


    /**
     * @var App\Models\System\User $user
     */
    public $user;

    /**
     * @var App\Models\System\Subscription $subscription
     */
    public $subscription;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, $subscription = null)
    {
        $this->user = $user;
        $this->subscription = $subscription;
    }
}
