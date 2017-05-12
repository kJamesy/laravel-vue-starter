<?php

namespace App\Notifications;

use App\Member;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MemberPasswordReset extends Notification
{
    use Queueable;

    public $token;
    public $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token, Member $user)
    {
        $this->token = $token;
        $this->user = $user;
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
        return (new MailMessage)
            ->subject('Reset Your ' . config('app.name') . ' Password')
            ->greeting('Hi ' . $this->user->first_name)
            ->line('You are receiving this email because we received a password reset request for your user account.')
            ->action('Reset Password', route('member.auth.show_password_reset_form', $this->token))
            ->line('If you did not request a password reset, no further action is required.');
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
