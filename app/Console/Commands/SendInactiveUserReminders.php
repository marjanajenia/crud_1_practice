<?php

namespace App\Console\Commands;

use App\Jobs\SendInactiveUserReminder;
use App\Models\User;
use Illuminate\Console\Command;

class SendInactiveUserReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:send-inactive-user-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::where('last_login_at', '<=', now()->subDays(7))
                     ->where(function($q){
                         $q->whereNull('last_reminder_sent_at')
                           ->orWhere('last_reminder_sent_at', '<', now()->subDay());
                     })
                     ->get();

        foreach ($users as $user) {
            SendInactiveUserReminder::dispatch($user);
        }

        $this->info('Inactive user reminders dispatched.');
    }

}
