<?php

namespace Modules\Notification\Http\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use function url;

class MainNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public string $textMessage;
    public array $notifications;

    public function __construct(string $textMessage, array $notifications = [])
    {
        $this->notifications = $notifications;
        $this->textMessage = $textMessage;
    }

    public function via(object $notifiable): array
    {
        return [];
    }

    public function toSms(): string
    {
        return $this->textMessage;
    }

    public function toEmail(): string
    {
        return $this->textMessage;
    }

    public function toTel(): string
    {
        return $this->textMessage;
    }

}
