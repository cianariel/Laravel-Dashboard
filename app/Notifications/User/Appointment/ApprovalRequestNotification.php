<?php

namespace App\Notifications\User\Appointment;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\System\User;
use App\Models\System\Office;

class ApprovalRequestNotification extends Notification
{
    use Queueable;

    /**
     * @var App\Models\System\User $owner
     */
    public $owner;
    /**
     * @var App\Models\System\User $repUser
     */
    public $repUser;
    /**
     * @var App\Models\System\Office $office
     */
    public $office;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $owner, User $repUser, Office $office)
    {
        $this->owner    = $owner;
        $this->repUser  = $repUser;
        $this->office   = $office;
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
        $owner      = $this->owner;
        $repUser    = $this->repUser;
        $office     = $this->office;

        return (new MailMessage)->subject(__('New Approval Request'))->markdown(
            'emails.user.appointment.approval_request',
            compact('owner', 'repUser', 'office')
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
