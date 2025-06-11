<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SecurityAlertNotification extends Notification
{
    use Queueable;

    protected $event;

    public function __construct($event)
    {
        $this->event = $event;
    }

    public function via($notifiable)
    {

        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Security Alert Notification')
                    ->line('Ada aktivitas kritikal/mencurigakan di aplikasi.')
                    ->line('Detail aktivitas:')
                    ->line($this->event->toString() ?? json_encode($this->event))
                    ->line('Segera cek dan tindak lanjuti.');
    }

    
}
