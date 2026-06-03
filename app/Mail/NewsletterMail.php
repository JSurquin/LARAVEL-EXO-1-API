<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;               // Permet à ce mailable d'être mis en file d'attente
use Illuminate\Contracts\Queue\ShouldQueue; // Interface signalant que l'envoi est asynchrone
use Illuminate\Mail\Mailable;               // Classe de base pour tous les mailables Laravel
use Illuminate\Mail\Mailables\Attachment;   // Représente une pièce jointe (non utilisée ici)
use Illuminate\Mail\Mailables\Content;      // Définit la vue et les données du corps de l'e-mail
use Illuminate\Mail\Mailables\Envelope;     // Définit l'objet (subject), expéditeur, destinataires
use Illuminate\Queue\SerializesModels;      // Sérialise les modèles Eloquent pour la queue (évite les données stale)
use App\Models\Newsletter;                  // Modèle injecté — contient subject et body

// Mailable — objet e-mail envoyé à chaque abonné pour une newsletter donnée
class NewsletterMail extends Mailable
{
    // Traits : mise en file d'attente + sérialisation du modèle Newsletter
    use Queueable, SerializesModels;

    /**
     * Injection du modèle Newsletter via le constructeur (promoted property).
     * Le modèle est automatiquement sérialisé/désérialisé par SerializesModels lors du passage en queue.
     */
    public function __construct(public Newsletter $newsletter)
    {
        //
    }

    /**
     * Enveloppe de l'e-mail : définit le sujet à partir du champ subject de la newsletter.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->newsletter->subject, // Sujet de l'e-mail = sujet de la newsletter
        );
    }

    /**
     * Corps de l'e-mail : pointe vers la vue Blade emails.newsletter
     * et lui transmet le modèle newsletter pour afficher subject et body.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.newsletter',                      // Vue Blade utilisée comme template HTML
            with: ['newsletter' => $this->newsletter]       // Variable disponible dans la vue
        );
    }

    /**
     * Pièces jointes — aucune pour cet e-mail.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
