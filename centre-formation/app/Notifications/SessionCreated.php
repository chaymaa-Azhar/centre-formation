<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\SessionCours;

class SessionCreated extends Notification
{
    use Queueable;

    protected $session;
    protected $role;

    /**
     * Create a new notification instance.
     */
    public function __construct(SessionCours $session, $role = 'etudiant')
    {
        $this->session = $session;
        $this->role = $role;
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
        $formationName = $this->session->formation->titre;
        
        $message = (new MailMessage)
                    ->subject("Nouveau planning : $formationName")
                    ->greeting("Bonjour " . $notifiable->prenom . " !");

        if ($this->role === 'formateur') {
            $message->line("L'administration vous a assigné une nouvelle session de cours.");
        } else {
            $message->line("Une nouvelle session vient d'être programmée pour votre formation !");
        }

        $joursStr = is_array($this->session->jours) ? implode(', ', $this->session->jours) : 'Non spécifié';

        $formateurName = $this->session->formateur ? $this->session->formateur->prenom . ' ' . $this->session->formateur->nom : 'Non assigné';

        $message->line("**Matière :** " . $this->session->matiere)
                ->line("**Formation :** " . $formationName)
                ->line("**Planning :**")
                ->line("- Du " . date('d/m/Y', strtotime($this->session->date_debut)) . " au " . date('d/m/Y', strtotime($this->session->date_fin)))
                ->line("- Jours : " . $joursStr)
                ->line("- De " . date('H:i', strtotime($this->session->heure_debut)) . " à " . date('H:i', strtotime($this->session->heure_fin)))
                ->line("- Formateur : " . $formateurName)
                ->action("Consulter mon planning", url('/login'))
                ->line("Merci de votre implication !");

        return $message;
    }
}
