<?php

namespace App\Console\Commands;

use App\Models\Session;
use App\Traits\HasCronLock;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

class CleanupExpiredSessionsCommand extends Command
{
    use HasCronLock;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'umaep:cleanup-sessions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired sessions from the database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $lockId = $this->acquireLock('cleanup_sessions');
        if (!$lockId) {
            $this->warn('Job is already running. Skipping...');
            return 0;
        }

        try {
            $lifetime = Config::get('session.lifetime', 120); // minutes
            $expiredAt = Carbon::now()->subMinutes($lifetime)->timestamp;

            $this->info("Cleaning up sessions older than {$lifetime} minutes...");

            $deletedCount = Session::where('last_activity', '<', $expiredAt)->delete();

            $this->info("Successfully deleted {$deletedCount} expired sessions.");
        }
        finally {
            $this->releaseLock('cleanup_sessions', $lockId);
        }

        return 0;
    }
}
