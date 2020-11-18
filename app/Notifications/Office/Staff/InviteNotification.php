<?php

namespace App\Notifications\Office\Staff;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\System\User;

class InviteNotification extends Notification
{
    use Queueable;

    /**
     * @var App\Models\System\User $owner
     */
    public $owner;
    /**
     * @var App\Models\System\User $guestUser
     */
    public $guestUser;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $owner, $guestUser)
    {
        $this->owner = $owner;
        $this->guestUser = $guestUser;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $owner = $this->owner;
        $guestUser = $this->guestUser;
        $office = $owner->offices()->first();

        return (new MailMessage)->subject(__('Invitation'))->markdown(
            'emails.office.staff.invitation',
            compact('owner', 'guestUser', 'office')
        );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
