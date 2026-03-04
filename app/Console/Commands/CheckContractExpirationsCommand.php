<?php

namespace App\Console\Commands;

use App\Models\Contract;
use App\Models\Notification;
use App\Traits\HasCronLock;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class CheckContractExpirationsCommand extends Command
{
    use HasCronLock;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'umaep:check-contracts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for contracts expiring in 7 days and notify parties';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $lockId = $this->acquireLock('check_contracts');
        if (!$lockId) {
            $this->warn('Job is already running. Skipping...');
            return 0;
        }

        try {
            $targetDate = Carbon::now()->addDays(7)->toDateString();
            $this->info("Searching for contracts expiring on: {$targetDate}");

            $expiringSoon = Contract::where('end_date', $targetDate)
                ->where('status', 'signed')
                ->get();

            if ($expiringSoon->isEmpty()) {
                $this->info('No contracts expiring in 7 days.');
                return 0;
            }

            foreach ($expiringSoon as $contract) {
                $this->info("Notifying for Contract: {$contract->id} - {$contract->title}");

                // Notify Party A
                Notification::create([
                    'profile_id' => $contract->party_a_profile_id,
                    'notification_type' => 'system',
                    'title' => 'Contract Expiring Soon',
                    'body' => "Your contract '{$contract->title}' is set to expire on {$contract->end_date->toDateString()}. Please review and take necessary action.",
                    'action_url' => "/contracts/{$contract->id}",
                    'is_read' => false,
                ]);

                // Notify Party B
                Notification::create([
                    'profile_id' => $contract->party_b_profile_id,
                    'notification_type' => 'system',
                    'title' => 'Contract Expiring Soon',
                    'body' => "Your contract '{$contract->title}' is set to expire on {$contract->end_date->toDateString()}. Please review and take necessary action.",
                    'action_url' => "/contracts/{$contract->id}",
                    'is_read' => false,
                ]);

                Log::info("Sent expiration notifications for Contract: {$contract->id}");
            }

            $this->info('Contract expiration sweep complete.');
        }
        finally {
            $this->releaseLock('check_contracts', $lockId);
        }

        return 0;
    }
}
