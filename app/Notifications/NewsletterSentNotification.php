<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;                              // Permet de mettre la notification en file d'attente
use Illuminate\Contracts\Queue\ShouldQueue;                // Interface signalant que la notification est asynchrone
use Illuminate\Notifications\Messages\MailMessage;         // Objet fluent pour construire l'e-mail de notification
use Illuminate\Notifications\Notification;                 // Classe de base pour toutes les notifications Laravel
use App\Models\Newsletter;                                 // Modèle newsletter dont on signale l'envoi

// Notification envoyée à l'admin une fois que le job d'envoi de newsletter est terminé
class NewsletterSentNotification extends Notification
{
    // Trait : met la notification en file d'attente pour ne pas bloquer le worker principal
    use Queueable;

    /**
     * Injection de la newsletter envoyée et du nombre d'abonnés touchés.
     */
    public function __construct(
        public Newsletter $newsletter, // Newsletter qui vient d'être distribuée
        public int $count              // Nombre d'abonnés ayant reçu l'e-mail
    ) {
        //
    }

    /**
     * Canaux de livraison de la notification.
     * Ici uniquement 'mail' — on pourrait ajouter 'database', 'slack', etc.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail']; // Envoi de la notification par e-mail
    }

    /**
     * Construit le message e-mail de la notification.
     * Utilise le builder fluent MailMessage : sujet + ligne de texte.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Newsletter envoyée !')  // Objet de l'e-mail de confirmation
            ->line('Votre newsletter "'.$this->newsletter->subject.'" a été envoyée à '.$this->count.' abonnés.');
            // Ligne principale du message avec le titre et le nombre de destinataires
    }
}
