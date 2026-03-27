<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\SessionCours;

class SessionUpdated extends Notification
{
    use Queueable;

    protected $session;
    protected $role;
    protected $changes;

    /**
     * Create a new notification instance.
     */
    public function __construct(SessionCours $session, $role = 'etudiant', $changes = [])
    {
        $this->session = $session;
        // $role can be 'etudiant' or 'formateur' to slightly adjust the message
        $this->role = $role;
        $this->changes = $changes;
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
        $roleName = $this->role === 'formateur' ? 'Formateur' : 'Étudiant';
        $formationName = $this->session->formation->titre;
        
        $message = (new MailMessage)
                    ->subject("Mise à jour du planning : $formationName")
                    ->greeting("Bonjour " . $notifiable->prenom . " !");

        if ($this->role === 'formateur') {
            $message->line("Une session de cours que vous animez a été modifiée par l'administration.");
        } else {
            $message->line("Une de vos sessions de cours a été mise à jour par l'administration.");
        }

        $joursStr = is_array($this->session->jours) ? implode(', ', $this->session->jours) : 'Non spécifié';

        $message->line("**Matière :** " . $this->session->matiere)
                ->line("**Formation :** " . $formationName);

        if (!empty($this->changes)) {
            $labels = [
                'formateur_id' => 'Le formateur',
                'date_debut' => 'La date de début',
                'date_fin' => 'La date de fin',
                'heure_debut' => "L'heure de début",
                'heure_fin' => "L'heure de fin",
                'jours' => 'Les jours de cours',
                'matiere' => 'La matière',
                'formation_id' => 'La formation'
            ];
            
            $modifiedItems = [];
            foreach ($this->changes as $key => $val) {
                if (isset($labels[$key])) {
                    $modifiedItems[] = $labels[$key];
                }
            }

            if (count($modifiedItems) > 0) {
                $verb = count($modifiedItems) > 1 ? 'ont été modifiés' : 'a été modifié';
                $sentence = implode(', ', $modifiedItems) . " " . $verb . ". Voici le nouveau planning après la modification :";
                $message->line(" ")->line("**" . $sentence . "**");
            }
        } else {
            $message->line(" ")->line("**Voici le nouveau planning complet :**");
        }

        $formateurName = $this->session->formateur ? $this->session->formateur->prenom . ' ' . $this->session->formateur->nom : 'Non assigné';

        $message->line("- Du " . date('d/m/Y', strtotime($this->session->date_debut)) . " au " . date('d/m/Y', strtotime($this->session->date_fin)))
                ->line("- Jours : " . $joursStr)
                ->line("- De " . date('H:i', strtotime($this->session->heure_debut)) . " à " . date('H:i', strtotime($this->session->heure_fin)))
                ->line("- Formateur : " . $formateurName)
                ->action("Consulter mon planning", url('/login'))
                ->line("Merci de votre attention !");

        return $message;
    }
}
