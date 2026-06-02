<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Mail\NewsletterMail;
use App\Models\Newsletter;
use App\Models\Subscriber;
use App\Models\User;
use App\Notifications\NewsletterSentNotification;
use Illuminate\Support\Facades\Mail;

class SendNewsletterJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(public Newsletter $newsletter, public User $admin)
    {
        //
    }

    public function handle(): void
    {
        $this->newsletter->refresh();
        if ($this->newsletter->sent_at) {
            return;
        }

        $subscribers = Subscriber::all();

        foreach ($subscribers as $subscriber) {
            Mail::to($subscriber->email)
                ->send(new NewsletterMail($this->newsletter));
        }

        $this->newsletter->update(['sent_at' => now()]);

        $this->admin->notify(
            new NewsletterSentNotification($this->newsletter, $subscribers->count())
        );
    }
}
