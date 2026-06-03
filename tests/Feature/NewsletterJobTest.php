<?php

use App\Jobs\SendNewsletterJob;                         // Job testé en appel direct (sans queue)
use App\Mail\NewsletterMail;                            // Mailable envoyé à chaque abonné
use App\Models\Newsletter;                              // Modèle newsletter
use App\Models\Subscriber;                              // Modèle abonné — factory count(3)
use App\Models\User;                                    // Admin notifié après envoi
use App\Notifications\NewsletterSentNotification;         // Notification de confirmation admin
use Illuminate\Support\Facades\Mail;                    // Façade Mail — fake() intercepte les envois
use Illuminate\Support\Facades\Notification;            // Façade Notification — fake() pour assertions

// Test : le job envoie un NewsletterMail par abonné (3 abonnés → 3 e-mails)
it('sends mail to subscribers', function () {
    Mail::fake(); // Remplace le transport mail : aucun e-mail réel n'est envoyé
    $admin = User::factory()->create();
    $newsletter = Newsletter::create(['subject' => 'Test', 'body' => 'Contenu']); // Newsletter en BDD
    Subscriber::factory()->count(3)->create(); // 3 abonnés factices

    (new SendNewsletterJob($newsletter, $admin))->handle(); // Exécute le job de façon synchrone (hors queue)

    Mail::assertSent(NewsletterMail::class, 3); // Vérifie que NewsletterMail a été « envoyé » exactement 3 fois
});

// Test : après exécution du job, sent_at est renseigné sur la newsletter
it('updates sent_at after job', function () {
    Mail::fake();
    Notification::fake(); // Désactive aussi les notifications pour isoler l'assertion sur sent_at
    $admin = User::factory()->create();
    $newsletter = Newsletter::create(['subject' => 'Test', 'body' => 'Contenu']);

    (new SendNewsletterJob($newsletter, $admin))->handle();

    expect($newsletter->fresh()->sent_at)->not->toBeNull(); // fresh() recharge depuis la BDD
});

// Test : l'admin reçoit une NewsletterSentNotification après l'envoi
it('notifies admin after send', function () {
    Mail::fake();
    Notification::fake(); // Permet Notification::assertSentTo() sans envoi réel
    $admin = User::factory()->create();
    $newsletter = Newsletter::create(['subject' => 'Test', 'body' => 'Contenu']);
    Subscriber::factory()->count(2)->create(); // 2 abonnés pour le compteur de la notification

    (new SendNewsletterJob($newsletter, $admin))->handle();

    Notification::assertSentTo($admin, NewsletterSentNotification::class); // Vérifie l'envoi à l'admin
});
