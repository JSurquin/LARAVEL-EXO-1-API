<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;       // Interface : ce job doit passer par la queue (asynchrone)
use Illuminate\Foundation\Queue\Queueable;         // Trait fournissant onQueue(), delay(), etc.
use App\Mail\NewsletterMail;                       // Mailable envoyé à chaque abonné
use App\Models\Newsletter;                         // Modèle newsletter à envoyer
use App\Models\Subscriber;                         // Modèle abonné — liste des destinataires
use App\Models\User;                               // Modèle utilisateur — l'admin notifié à la fin
use App\Notifications\NewsletterSentNotification;  // Notification envoyée à l'admin après l'envoi
use Illuminate\Support\Facades\Mail;               // Façade d'envoi d'e-mails Laravel

// Job asynchrone : envoie la newsletter à tous les abonnés, puis notifie l'admin
class SendNewsletterJob implements ShouldQueue
{
    // Trait Queueable : active onQueue(), delay(), afterCommit(), etc.
    use Queueable;

    // Nombre maximum de tentatives en cas d'erreur (avant que le job soit marqué « failed »)
    public int $tries = 3;

    /**
     * Injection de la newsletter à envoyer et de l'admin à notifier.
     * Les modèles sont sérialisés par le trait SerializesModels (via Queueable) pour la queue.
     */
    public function __construct(public Newsletter $newsletter, public User $admin)
    {
        //
    }

    /**
     * Logique principale du job — exécutée par le worker de queue.
     */
    public function handle(): void
    {
        // Recharge le modèle depuis la BDD pour s'assurer que sent_at est à jour
        // (un autre worker a peut-être déjà traité ce job en cas de rejeu)
        $this->newsletter->refresh();

        // Idempotence : si la newsletter a déjà été envoyée, on ne renvoie pas
        if ($this->newsletter->sent_at) {
            return;
        }

        // Récupère tous les abonnés enregistrés en base
        $subscribers = Subscriber::all();

        // Envoie un e-mail individuel à chaque abonné via le mailable NewsletterMail
        foreach ($subscribers as $subscriber) {
            Mail::to($subscriber->email)
                ->send(new NewsletterMail($this->newsletter));
        }

        // Marque la newsletter comme envoyée avec l'horodatage courant
        $this->newsletter->update(['sent_at' => now()]);

        // Notifie l'admin par e-mail : newsletter envoyée + nombre d'abonnés touchés
        $this->admin->notify(
            new NewsletterSentNotification($this->newsletter, $subscribers->count())
        );
    }
}
