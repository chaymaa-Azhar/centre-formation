<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InscriptionStatusUpdated extends Notification
{
    use Queueable;

    protected $formationTitle;
    protected $status;

    /**
     * Create a new notification instance.
     */
    public function __construct($formationTitle, $status)
    {
        $this->formationTitle = $formationTitle;
        $this->status = $status;
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
        $statusText = ($this->status === 'Validé') ? 'acceptée' : 'refusée';
        $color = ($this->status === 'Validé') ? '#28a745' : '#dc3545';

        return (new MailMessage)
                    ->subject('Mise à jour de votre inscription - ' . $this->formationTitle)
                    ->greeting('Bonjour ' . $notifiable->prenom . ' !')
                    ->line('Nous vous informons que votre demande d\'inscription pour la formation **' . $this->formationTitle . '** a été **' . $statusText . '**.')
                    ->action('Accéder à mon espace', url('/login'))
                    ->line('Merci de votre confiance !')
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
            'status' => $this->status,
        ];
    }
}
