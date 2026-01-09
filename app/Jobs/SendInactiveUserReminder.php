<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendInactiveUserReminder implements ShouldQueue
{
    use Queueable;
    protected $user;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::raw("Hi {$this->user->name}, you have been inactive for 7 days!", function ($message) {
            $message->to($this->user->email)
                    ->subject('We miss you!');
        });

        // Update last reminder timestamp
        $this->user->update([
            'last_reminder_sent_at' => now(),
        ]);
    }
}
