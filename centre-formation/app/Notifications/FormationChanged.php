<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Formation;

class FormationChanged extends Notification
{
    use Queueable;

    protected $oldFormation;
    protected $newFormation;

    /**
     * Create a new notification instance.
     */
    public function __construct(Formation $oldFormation, Formation $newFormation)
    {
        $this->oldFormation = $oldFormation;
        $this->newFormation = $newFormation;
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
                    ->subject("Mise à jour de votre formation : " . $this->newFormation->titre)
                    ->greeting("Bonjour " . $notifiable->prenom . " !")
                    ->line("L'administration a modifié votre formation principale au sein de notre centre.")
                    ->line("**Ancienne Formation :** " . $this->oldFormation->titre)
                    ->line("**Nouvelle Formation :** " . $this->newFormation->titre)
                    ->line("**Prix de la nouvelle formation :** " . number_format($this->newFormation->prix, 2, ',', ' ') . " DH")
                    ->line("Votre planning et vos notes ont été mis à jour dans votre espace personnel.")
                    ->action("Accéder à mon espace", url('/login'))
                    ->line("Si vous avez des questions, n'hésitez pas à nous contacter.");
    }
}
