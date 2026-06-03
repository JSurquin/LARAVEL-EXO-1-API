<?php

use App\Jobs\SendNewsletterJob;
use App\Mail\NewsletterMail;
use App\Models\Newsletter;
use App\Models\Subscriber;
use App\Models\User;
use App\Notifications\NewsletterSentNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

it('sends mail to subscribers', function () {
    Mail::fake();
    $admin = User::factory()->create();
    $newsletter = Newsletter::create(['subject' => 'Test', 'body' => 'Contenu']);
    Subscriber::factory()->count(3)->create();

    (new SendNewsletterJob($newsletter, $admin))->handle();

    Mail::assertSent(NewsletterMail::class, 3);
});

it('updates sent_at after job', function () {
    Mail::fake();
    Notification::fake();
    $admin = User::factory()->create();
    $newsletter = Newsletter::create(['subject' => 'Test', 'body' => 'Contenu']);

    (new SendNewsletterJob($newsletter, $admin))->handle();

    expect($newsletter->fresh()->sent_at)->not->toBeNull();
});

it('notifies admin after send', function () {
    Mail::fake();
    Notification::fake();
    $admin = User::factory()->create();
    $newsletter = Newsletter::create(['subject' => 'Test', 'body' => 'Contenu']);
    Subscriber::factory()->count(2)->create();

    (new SendNewsletterJob($newsletter, $admin))->handle();

    Notification::assertSentTo($admin, NewsletterSentNotification::class);
});