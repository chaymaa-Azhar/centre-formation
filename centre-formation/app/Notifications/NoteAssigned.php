<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NoteAssigned extends Notification
{
    use Queueable;

    protected $formationTitle;
    protected $value;

    /**
     * Create a new notification instance.
     */
    public function __construct($formationTitle, $value)
    {
        $this->formationTitle = $formationTitle;
        $this->value = $value;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Nouvelle note disponible - ' . $this->formationTitle)
                    ->greeting('Bonjour ' . $notifiable->prenom . ' !')
                    ->line('Une nouvelle note a été publiée pour votre formation **' . $this->formationTitle . '**.')
                    ->line('Votre note est de : **' . $this->value . ' / 20**.')
                    ->action('Consulter mon relevé de notes', url('/login'))
                    ->line('Félicitations pour vos efforts !')
                    ->salutation('L\'équipe du Centre de Formation');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'formation' => $this->formationTitle,
            'note' => $this->value,
        ];
    }
}
