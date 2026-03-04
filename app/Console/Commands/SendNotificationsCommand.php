<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Traits\HasCronLock;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class SendNotificationsCommand extends Command
{
    use HasCronLock;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'umaep:send-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch pending notifications';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $lockId = $this->acquireLock('send_notifications');
        if (!$lockId) {
            $this->warn('Job is already running. Skipping...');
            return 0;
        }

        try {
            $this->info('Starting notification dispatch...');

            // Fetch unread notifications that haven't been "sent" (mocked)
            $notifications = Notification::where('is_read', false)
                ->whereNull('read_at')
                ->limit(100)
                ->get();

            if ($notifications->isEmpty()) {
                $this->info('No notifications to send.');
                return 0;
            }

            foreach ($notifications as $notification) {
                // Mocking external dispatch (e.g., Email, SMS, Push)
                Log::info("Dispatching notification: [{$notification->id}] {$notification->title}");

                // In a real scenario, we'd mark it as sent in a 'sent_at' column
                // For now, we just log and move on to demonstrate the command logic.
                $this->line("Processed: {$notification->id}");
            }

            $this->info('Notification dispatch complete.');
        }
        finally {
            $this->releaseLock('send_notifications', $lockId);
        }

        return 0;
    }
}
