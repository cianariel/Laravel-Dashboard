<?php

namespace App\Notifications\Office\Messages;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\System\User;
use App\Models\System\Role;
use App\Models\System\Message;

class RepNewMessageNotification extends Notification
{
    use Queueable;

    /**
     * @var App\Models\System\User $to
     */
    public $to;

    /**
     * @var App\Models\System\User $from
     */
    public $from;

    /**
     * @var App\Models\System\Message $message
     */
    public $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $to, User $from, Message $message)
    {
        $this->to = $to;
        $this->from = $from;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $to = $this->to;
        $from = $this->from;
        $message = $this->message;
        $subject = $message->subject?? 'New Message';
        $officeOwner = office_owner($from);
        $office = $officeOwner->offices()->first();

        return (new MailMessage)->subject($subject)
                                ->markdown(
                                    'emails.office.messages.reps.generic_message',
                                    compact(
                                        'to',
                                        'from',
                                        'message',
                                        'subject',
                                        'office'
                                    )
                                );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
