<?php

namespace App\Notifications;

use App\Models\WorkStudent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StudentUpAssignment extends Notification
{
    use Queueable;

    protected $work;

    public function __construct(WorkStudent $work)
    {
        $this->work = $work;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Enviar notificación por correo y almacenarla en la base de datos
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'work' => $this->work,
        ];
    }
}
