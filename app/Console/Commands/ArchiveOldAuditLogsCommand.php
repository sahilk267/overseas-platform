<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
use App\Traits\HasCronLock;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;

class ArchiveOldAuditLogsCommand extends Command
{
    use HasCronLock;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'umaep:archive-audit-logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archive audit logs older than 30 days into a flat file and delete from database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $lockId = $this->acquireLock('archive_audit_logs');
        if (!$lockId) {
            $this->warn('Job is already running. Skipping...');
            return 0;
        }

        try {
            $daysToKeep = 30;
            $cutoffDate = Carbon::now()->subDays($daysToKeep);

            $this->info("Archiving audit logs older than {$daysToKeep} days ({$cutoffDate->toDateString()})...");

            $logsToArchive = AuditLog::where('created_at', '<', $cutoffDate)->get();

            if ($logsToArchive->isEmpty()) {
                $this->info('No old logs found to archive.');
                return 0;
            }

            $archiveDir = storage_path('logs/archives');
            if (!File::exists($archiveDir)) {
                File::makeDirectory($archiveDir, 0755, true);
            }

            $filename = "audit_logs_archive_" . Carbon::now()->format('Y_m_d_His') . ".json";
            $filePath = $archiveDir . DIRECTORY_SEPARATOR . $filename;

            // Serialize and save to file
            File::put($filePath, $logsToArchive->toJson(JSON_PRETTY_PRINT));

            $this->info("Successfully archived " . $logsToArchive->count() . " logs to: {$filePath}");

            // Delete from database
            AuditLog::whereIn('id', $logsToArchive->pluck('id'))->delete();

            $this->info("Successfully cleared " . $logsToArchive->count() . " logs from the database.");
        }
        catch (\Exception $e) {
            $this->error("Failed to archive logs: " . $e->getMessage());
            return 1;
        }
        finally {
            $this->releaseLock('archive_audit_logs', $lockId);
        }

        return 0;
    }
}
