<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountCredentialsSent extends Notification
{
    use Queueable;

    protected $password;
    protected $isNewAccount;

    /**
     * Create a new notification instance.
     *
     * @param string $password
     * @param bool $isNewAccount True if this is a new account, false if password was reset
     */
    public function __construct($password, $isNewAccount = true)
    {
        $this->password = $password;
        $this->isNewAccount = $isNewAccount;
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
        $subject = $this->isNewAccount 
            ? 'Bienvenue au Centre de Formation - Vos identifiants' 
            : 'Mise à jour de votre mot de passe';
        
        $message = (new MailMessage)
                    ->subject($subject)
                    ->greeting('Bonjour ' . $notifiable->prenom . ' !');

        if ($this->isNewAccount) {
            $message->line('Un compte a été créé pour vous par l\'administration.');
        } else {
            $message->line('Votre mot de passe a été réinitialisé par l\'administration.');
        }

        return $message->line('Voici vos identifiants pour accéder à votre espace :')
                    ->line('**Email :** ' . $notifiable->email)
                    ->line('**Mot de passe :** ' . $this->password)
                    ->action('Se connecter à mon espace', url('/login'))
                    ->line('Nous vous conseillons de conserver cet email en sécurité.')
                    ->salutation('L\'équipe du Centre de Formation');
    }
}
